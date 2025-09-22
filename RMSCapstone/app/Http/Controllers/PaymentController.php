<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\ServiceBag;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentUploadedMail;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    protected ServiceBag $service;
    protected PaymentService $paymentService;

    public function boot(ServiceBag $services)
    {
        $this->paymentService = $services->paymentService;
    }

    /**
     * Handles incoming PayMongo webhook events.
     *
     * This function:
     * - Verifies the webhook signature using the PAYMONGO_WEBHOOK_SECRET.
     * - Parses the event payload and checks for specific payment events.
     * - If the event is a successful payment (e.g., 'payment.paid'), it stores the payment details in the database.
     * - Logs various stages for debugging and monitoring purposes.
     * 
     * @param Request $request The incoming HTTP request containing the webhook payload and headers.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or error.
     */

    public function webhook(Request $request, PaymentService $paymentService)
    {
        // Log that the webhook endpoint has been hit
        Log::info('Webhook Triggered');

        try {
            // Retrieve the PayMongo webhook secret from the environment file
            $webhook_secret = env('PAYMONGO_WEBHOOK_SECRET');

            // Get the signature header from the incoming request
            $webhook_signature = $request->header('Paymongo-Signature');

            // Get the raw body content of the request (JSON payload)
            $payload = $request->getContent();

            // Decode the JSON payload into an associative array
            $event = json_decode($payload, true);

            // If there's no signature in the header, log and return a 400 response
            if (!$webhook_signature) {
                Log::error('No Paymongo-Signature header present.');
                return response()->json(['error' => 'Signature header missing.'], 400);
            }

            // Parse the signature header into key/value pairs
            $parts = explode(',', $webhook_signature);
            $parsed = [];

            foreach ($parts as $part) {
                [$key, $value] = array_pad(explode('=', $part, 2), 2, null);
                if ($key && $value !== null && $value !== '') {
                    $parsed[trim($key)] = trim($value);
                }
            }

            // Support both standard PayMongo format (v1) and observed format (li)
            $timestamp = $parsed['t'] ?? null;
            $signature = $parsed['v1'] ?? ($parsed['li'] ?? null);

            // If parsing fails or components are missing, return a 400 error
            if (!$timestamp || !$signature) {
                Log::error('Webhook signature parsing failed.', ['signature' => $webhook_signature]);
                return response()->json(['error' => 'Invalid signature format.'], 400);
            }

            // Build the signed payload to verify the signature
            $signedPayload = $timestamp . '.' . $payload;

            // Compute the expected HMAC SHA256 signature
            $computedSignature = hash_hmac('sha256', $signedPayload, $webhook_secret);

            // Check if the computed signature matches the received one securely
            if (!hash_equals($computedSignature, $signature)) {
                Log::warning('Invalid webhook signature.', [
                    'computed' => $computedSignature,
                    'received' => $signature,
                ]);
                return response()->json(['error' => 'Invalid signature.'], 403);
            }

            // Log the full payload for debugging
            Log::info('Webhook full payload:', ['event' => $event]);

            // Extract event type and relevant payment attributes
            // Data structure : event-> data -> attributes -> type || data
            $eventType = $event['data']['attributes']['type'] ?? ''; // event->data->attrbutes->type
            $data = $event['data']['attributes']['data'] ?? []; //  event->data->attributes->data
            $attributes = $data['attributes'] ?? []; // event->data->attributes->data->attributes

            // Handle 'payment.paid' and 'payment.failed' events
            if ($eventType === 'payment.paid' || $eventType === 'payment.failed') {

                $amountPaid = $attributes['amount'] ?? 0;
                $modeOfPayment = $attributes['source']['type'] ?? 'unknown';
                $paymentReferenceNumber = $data['id'] ?? null;
                $metadata = $attributes['metadata'] ?? [];

                $invoiceId = (int)($metadata['invoice_id'] ?? 0);
                $convenienceFee = (int)($metadata['convenience_fee'] ?? 0);
                $invoice = Invoice::find($invoiceId);

                if (!$invoice) {
                    Log::error("Invoice not found.", ['invoice_id' => $invoiceId]);
                    return response()->json(['error' => 'Invoice not found.'], 404);
                }

                $transaction = $invoice->transaction;

                $convenienceFeeInPesos = $convenienceFee / 100;
                $amountInPesos = $amountPaid / 100;

                try {
                    DB::transaction(function () use (
                        $eventType,
                        $amountInPesos,
                        $convenienceFeeInPesos,
                        $invoice,
                        $invoiceId,
                        $modeOfPayment,
                        $paymentReferenceNumber,
                        $metadata,
                        $paymentService,
                        $transaction,
                    ) {
                        // Save payment record regardless of event type
                        $payment = Payment::create([
                            'amount_paid' => $amountInPesos,
                            'convenience_fee' => $convenienceFeeInPesos,
                            'invoice_id' => $invoiceId,
                            'mode_of_payment' => $modeOfPayment,
                            'payment_type' => trim($metadata['payment_type'] ?? 'unknown'),
                            'payment_reference_number' => $paymentReferenceNumber,
                            'payment_date' => now(),
                            'verified_at' => now(),
                            'notes' => trim($metadata['notes'] ?? 'not defined'),
                            'payment_status' => $eventType === 'payment.paid' ? 'completed' : 'failed',

                        ]);

                        // Proceed only if payment was successful
                        if ($eventType === 'payment.paid') {

                            $newAmountPaid = $invoice->amount_paid + $payment->amount_paid;
                            $newBalanceDue = max($invoice->sub_total - $newAmountPaid, 0);

                            $invoice->update([
                                'amount_paid' => $newAmountPaid,
                                'balance_due' => $newBalanceDue,
                            ]);

                            if ($newBalanceDue == 0) {
                                $invoice->update([
                                    'invoice_status' => 'completed',
                                    'completed_at' => now(),
                                ]);
                            }

                            if ($transaction && $transaction->transaction_status === 'pending') {
                                $transaction->update([
                                    'transaction_status' => 'receipt_verified',
                                    'updated_at' => now(),
                                ]);
                            }

                            $user = $transaction->transactionUser;
                            if (!$user) {
                                throw new \Exception("No user associated with transaction ID {$transaction->id}");
                            }

                            $paymentService->applyPaymentToUnpaidItems($transaction, $payment->amount_paid);


                            $paymentDetails = [
                                'full_name' => $user->first_name . ' ' . $user->last_name,
                                'email' => $user->email,
                                'payment_method_id' => $payment->mode_of_payment,
                                'transaction_id' => $transaction->id,
                                'payment_reference_number' => $payment->payment_reference_number,
                                'notes' => $payment->notes,
                                'check_in' => $transaction->start_datetime,
                                'check_out' => $transaction->end_datetime,
                                'total_amount' => $transaction->total_amount,
                                'deposit' => $transaction->deposit_amount,
                            ];

                            // Send email only for successful payments
                            Mail::to($paymentDetails['email'])->send(new PaymentUploadedMail($paymentDetails));
                        }
                    });

                    Log::info("Payment {$eventType} saved in database.", ['event_type' => $eventType, 'attributes' => $attributes]);

                    return response()->json(['message' => "Payment {$eventType} saved."], 200);
                } catch (\Exception $e) {
                    Log::error("Failed to process {$eventType}: " . $e->getMessage());
                    return response()->json(['error' => 'Processing failed.'], 500);
                }
            }

            // Log and return if the event type is not handled
            Log::info('Webhook event not handled.', ['event_type' => $eventType]);
            return response()->json(['message' => 'Event not handled.'], 200);
        } catch (\Exception $e) {
            // Catch and log any unexpected server errors
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_body' => $request->getContent(),
            ]);
            return response()->json(['error' => 'Server error.'], 500);
        }
    }

    public function failed()
    {
        return view('guest.proof-of-payment-page');
    }
}





// s



// try {
//     $webhook_secret = env('PAYMONGO_WEBHOOK_SECRET');
//     $webhook_signature = $request->header('Paymongo-Signature');
//     $event_datas = $request->getContent();
//     $event_filter = json_decode($event_datas, true);

//     // Signature verification
//     $webhook_signature_raw = preg_split("/,/", $webhook_signature);
//     $webhook_signature_raw_time = preg_split("/=/", $webhook_signature_raw[0]);
//     $webhook_signature_raw_data = preg_split("/=/", $webhook_signature_raw[1]);

//     $webhook_signature_time = $webhook_signature_raw_time[1] ?? null;
//     $webhook_signature_data = $webhook_signature_raw_data[1] ?? null;

//     if (!$webhook_signature_time || !$webhook_signature_data) {
//         Log::error('Webhook signature parsing failed.', ['signature' => $webhook_signature]);
//         return response()->json(['error' => 'Invalid signature format.'], 400);
//     }

//     $webhook_time_with_json_data = $webhook_signature_time . '.' . $event_datas;
//     $computedSignature = hash_hmac('sha256', $webhook_time_with_json_data, $webhook_secret);
//     $mySignature = hash_equals($computedSignature, $webhook_signature_data);

//     if ($mySignature === true) {
//         if ($event_filter['data']['type'] === 'link.payment.paid') {
//             $attributes = $event_filter['data']['attributes'];
//             $metadata = $attributes['metadata'] ?? [];

//             Payment::create([
//                 // 'invoice_id' => isset($metadata['invoice_id']) ? (int)$metadata['invoice_id'] : null,
//                 'amount_paid' => $attributes['amount'] / 100,
//             ]);

//             Log::info('Payment saved via webhook.', ['payment_data' => $attributes]);

//             return response()->json(['message' => 'Payment saved.'], 200);
//         }

//         Log::info('Webhook event not handled.', ['event_type' => $event_filter['data']['type']]);
//         return response()->json(['message' => 'Event not handled.'], 200);
//     }

//     Log::warning('Invalid webhook signature.', [
//         'computed' => $computedSignature,
//         'received' => $webhook_signature_data,
//     ]);
//     return response()->json(['error' => 'Invalid signature.'], 403);
// } catch (\Exception $e) {

//     Log::error('Webhook processing error: ' . $e->getMessage(), [
//         'trace' => $e->getTraceAsString(),
//         'request_body' => $request->getContent(),
//     ]);
//     return response()->json(['error' => 'Server error.'], 500);
// }




// if ($eventType === 'payment.paid') {

//     $amountPaid = $attributes['amount'] ?? 0;
//     $modeOfPayment = $attributes['source']['type'] ?? 'unknown';
//     $paymentReferenceNumber = $data['id'] ?? null;
//     $metadata = $attributes['metadata'] ?? [];

//     $invoiceId = (int)($metadata['invoice_id'] ?? 0);
//     Log::info("Processing payment for invoice ID: {$invoiceId}");

//     $invoice = $this->invoice ?? Invoice::find($invoiceId);

//     if (!$invoice) {
//         Log::error("Invoice not found.", ['invoice_id' => $invoiceId]);
//         return response()->json(['error' => 'Invoice not found.'], 404);
//     }

//     $transaction = $invoice?->transaction;
//     $amountInPesos = $amountPaid / 100;

//     if ($invoice->balance_due < $amountInPesos) {
//         Log::warning('Payment exceeds balance due.', [
//             'invoice_id' => $invoiceId,
//             'amount_paid' => $amountInPesos,
//             'balance_due' => $invoice->balance_due,
//         ]);
//         return response()->json(['error' => 'Payment exceeds balance due.'], 400);
//     }

//     try {

//         DB::transaction(function () use (&$paymentDetails, $amountInPesos, $invoice, $invoiceId, $modeOfPayment, $paymentReferenceNumber, $metadata, $transaction) {

//             $payment = Payment::create([
//                 'amount_paid' => $amountInPesos,
//                 'invoice_id' => $invoiceId,
//                 'mode_of_payment' => $modeOfPayment,
//                 'payment_type' => trim($metadata['payment_type'] ?? 'unknown'),
//                 'payment_reference_number' => $paymentReferenceNumber,
//                 'payment_date' => now(),
//                 'verified_at' => now(),
//                 'notes' => trim($metadata['notes'] ?? 'not defined'),
//                 'payment_status' => 'completed',
//             ]);


//             // Update the invoice with the new amount paid and balance due
//             $newAmountPaid = $invoice->amount_paid + $payment->amount_paid;
//             $newBalanceDue = max($invoice->sub_total - $newAmountPaid, 0);

//             $invoice->update([
//                 'amount_paid' => $newAmountPaid,
//                 'balance_due' => $newBalanceDue,
//             ]);

//             // Log invoice update
//             Log::info("Invoice updated: Amount Paid - {$newAmountPaid}, Balance Due - {$newBalanceDue}");

//             // If the balance is 0, update invoice status to 'completed'
//             if ($newBalanceDue == 0) {
//                 $invoice->update([
//                     'invoice_status' => 'completed',
//                     'completed_at' => now(),
//                 ]);

//                 // Log status change
//                 Log::info("Invoice status updated to 'completed' because balance due is 0.");
//             }


//             if ($transaction && $transaction->transaction_status === 'pending') {
//                 $transaction->update([
//                     'transaction_status' => 'receipt_verified',
//                     'updated_at' => now(),
//                 ]);
//             }

//             $user = $transaction->transactionUser;

//             if (!$user) {
//                 throw new \Exception("No user associated with transaction ID {$transaction->id}");
//             }

//             $paymentDetails = [
//                 'full_name' => $user->first_name . ' ' . $user->last_name,
//                 'email' => $user->email,
//                 'payment_method_id' => $payment->mode_of_payment,
//                 'transaction_id' => $transaction->id,
//                 'payment_reference_number' => $payment->payment_reference_number,
//                 'notes' => $payment->notes,
//                 'check_in' => $transaction->start_datetime,
//                 'check_out' => $transaction->end_datetime,
//                 'total_amount' => $transaction->total_amount,
//                 'deposit' => $transaction->deposit_amount,
//             ];
//         });

//         // Send mail AFTER transaction commits successfully
//         Mail::to($paymentDetails['email'])->send(new PaymentUploadedMail($paymentDetails));

//         Log::info('Payment saved via webhook.', ['payment_data' => $attributes]);
//         return response()->json(['message' => 'Payment saved.'], 200);
//     } catch (\Exception $e) {
//         Log::error('Webhook payment processing failed: ' . $e->getMessage());
//         return response()->json(['error' => 'Processing failed.'], 500);
//     }
// }
