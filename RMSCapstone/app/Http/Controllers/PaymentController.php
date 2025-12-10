<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\InvoiceService;
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
    protected InvoiceService $invoiceService;

    public function boot(ServiceBag $services)
    {
        $this->paymentService = $services->paymentService;
        $this->invoiceService = $services->invoiceService;
    }

    /**
     * Handles incoming PayMongo webhook events safely and reliably
     * Always returns 200 to PayMongo while maintaining security
     */
    public function webhook(Request $request, PaymentService $paymentService, InvoiceService $invoiceService)
    {
        Log::info('PayMongo Webhook Received', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Prepare immediate 200 response (to prevent webhook disabling)
        $successResponse = response()->json([
            'status' => 'success',
            'message' => 'Webhook processed',
            'timestamp' => now()->toISOString()
        ], 200);

        try {
            $webhook_secret = env('PAYMONGO_WEBHOOK_SECRET');
            $webhook_signature = $request->header('Paymongo-Signature');
            $payload = $request->getContent();

            // SECURITY: Validate required components
            if (!$webhook_secret) {
                Log::error('❌ SECURITY: PAYMONGO_WEBHOOK_SECRET not configured');
                return $successResponse;
            }

            if (!$webhook_signature) {
                Log::error('❌ SECURITY: No Paymongo-Signature header present');
                return $successResponse;
            }

            if (empty($payload)) {
                Log::warning('⚠️ Empty webhook payload received');
                return $successResponse;
            }

            // Parse and validate webhook signature
            $signatureValid = $this->validateWebhookSignature($webhook_signature, $payload, $webhook_secret);
            if (!$signatureValid) {
                // Already logged in validateWebhookSignature, just return 200
                return $successResponse;
            }

            // Parse JSON payload
            $event = json_decode($payload, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('❌ Invalid JSON in webhook payload', ['error' => json_last_error_msg()]);
                return $successResponse;
            }

            // Validate webhook structure
            if (!$this->isValidWebhookStructure($event)) {
                Log::error('❌ Invalid webhook structure', ['event_keys' => array_keys($event)]);
                return $successResponse;
            }

            Log::info('✅ Webhook authenticated and validated', [
                'event_type' => $event['data']['attributes']['type'] ?? 'unknown',
                'event_id' => $event['data']['id'] ?? 'unknown'
            ]);

            // Process the webhook
            $this->processWebhookEvent($event, $paymentService, $invoiceService);

            Log::info('✅ Webhook processing completed successfully');
            return $successResponse;
        } catch (\Exception $e) {
            Log::error('💥 Unexpected webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);
            return $successResponse; // Always return 200
        }
    }

    /**
     * Validate PayMongo webhook signature securely
     */
    private function validateWebhookSignature(string $webhook_signature, string $payload, string $webhook_secret): bool
    {
        try {
            // Parse signature header flexibly
            $signatureParts = [];
            $parts = explode(',', $webhook_signature);

            foreach ($parts as $part) {
                if (strpos($part, '=') !== false) {
                    [$key, $value] = explode('=', $part, 2);
                    $key = trim($key);
                    $value = trim($value);
                    if ($key && $value) {
                        $signatureParts[$key] = $value;
                    }
                }
            }

            // Support multiple signature formats
            $timestamp = $signatureParts['t'] ?? $signatureParts['timestamp'] ?? null;
            $signature = $signatureParts['v1'] ?? $signatureParts['signature'] ?? $signatureParts['li'] ?? null;

            if (!$timestamp || !$signature) {
                Log::error('SECURITY: Missing timestamp or signature in header', [
                    'parsed_parts' => $signatureParts,
                    'raw_signature' => $webhook_signature
                ]);
                return false;
            }

            // Verify signature hasn't expired (optional but recommended)
            if (!$this->isValidTimestamp($timestamp)) {
                Log::error('SECURITY: Webhook timestamp expired or invalid', ['timestamp' => $timestamp]);
                return false;
            }

            // Compute and verify HMAC signature
            $signedPayload = $timestamp . '.' . $payload;
            $computedSignature = hash_hmac('sha256', $signedPayload, $webhook_secret);

            if (!hash_equals($computedSignature, $signature)) {
                Log::error('SECURITY: Webhook signature mismatch - potential fraud', [
                    'computed' => substr($computedSignature, 0, 8) . '...',
                    'received' => substr($signature, 0, 8) . '...',
                    'timestamp' => $timestamp
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('SECURITY: Signature validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate webhook timestamp to prevent replay attacks
     */
    private function isValidTimestamp(string $timestamp): bool
    {
        try {
            $webhookTime = intval($timestamp);
            $currentTime = time();
            $timeDifference = abs($currentTime - $webhookTime);

            // Allow 5-minute tolerance for clock skew and delivery delays
            return $timeDifference <= 300;
        } catch (\Exception $e) {
            Log::error('Timestamp validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate webhook payload structure
     */
    private function isValidWebhookStructure(array $event): bool
    {
        // Basic structure validation
        if (
            !isset($event['data']) || !is_array($event['data']) ||
            !isset($event['data']['attributes']) || !is_array($event['data']['attributes'])
        ) {
            return false;
        }

        $eventType = $event['data']['attributes']['type'] ?? '';

        // For payment events, validate required nested structure
        if (in_array($eventType, ['payment.paid', 'payment.failed'])) {
            if (
                !isset($event['data']['attributes']['data']['attributes']) ||
                !isset($event['data']['attributes']['data']['id'])
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Process validated webhook event
     */
    private function processWebhookEvent(array $event, PaymentService $paymentService, InvoiceService $invoiceService): void
    {
        $eventType = $event['data']['attributes']['type'] ?? '';
        $data = $event['data']['attributes']['data'] ?? [];
        $attributes = $data['attributes'] ?? [];

        Log::info('Processing webhook event', ['event_type' => $eventType]);

        if ($eventType === 'payment.paid' || $eventType === 'payment.failed') {
            $this->processPaymentEvent($eventType, $attributes, $data, $paymentService, $invoiceService);
        } else {
            Log::info('Unhandled webhook event type', ['event_type' => $eventType]);
        }
    }

    /**
     * Process payment events (paid or failed)
     */
    private function processPaymentEvent(string $eventType, array $attributes, array $data, PaymentService $paymentService, InvoiceService $invoiceService): void
    {
        try {
            $modeOfPayment = $attributes['source']['type'] ?? 'unknown';
            $paymentReferenceNumber = $data['id'] ?? null;
            $metadata = $attributes['metadata'] ?? [];
            $invoiceId = (int)($metadata['invoice_id'] ?? 0);

            Log::info('Processing payment event', [
                'event_type' => $eventType,
                'invoice_id' => $invoiceId,
                'payment_reference' => $paymentReferenceNumber
            ]);

            // Find invoice - if not found, log and return (but don't throw)
            $invoice = Invoice::find($invoiceId);
            if (!$invoice) {
                Log::error('Invoice not found for webhook payment', ['invoice_id' => $invoiceId]);
                return;
            }

            $transaction = $invoice->transaction;
            $amountInPesos = ($attributes['amount'] ?? 0) / 100;
            $convenienceFeeInPesos = (float)($metadata['convenience_fee'] ?? 0);

            // Process within transaction
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
                $invoiceService
            ) {
                // Create payment record
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

                Log::info('💳 Payment record created', [
                    'payment_id' => $payment->id,
                    'status' => $payment->payment_status,
                    'amount' => $amountInPesos
                ]);

                // Process successful payment
                if ($eventType === 'payment.paid') {
                    $this->processSuccessfulPayment($payment, $invoice, $transaction, $paymentService, $invoiceService, $metadata);
                }
            });

            Log::info('Payment event processed successfully', [
                'event_type' => $eventType,
                'invoice_id' => $invoiceId
            ]);
        } catch (\Exception $e) {
            Log::error('Payment event processing failed: ' . $e->getMessage(), [
                'event_type' => $eventType,
                'invoice_id' => $invoiceId ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            // Don't re-throw - we've already returned 200 to PayMongo
        }
    }

    /**
     * Process successful payment
     */
    private function processSuccessfulPayment($payment, $invoice, $transaction, $paymentService, $invoiceService, $metadata): void
    {
        // Update invoice amounts
        $newAmountPaid = $invoice->amount_paid + $payment->amount_paid;
        $newBalanceDue = max($invoice->sub_total - $newAmountPaid, 0);

        $invoice->update([
            'amount_paid' => $newAmountPaid,
            'balance_due' => $newBalanceDue,
        ]);

        Log::info('📊 Invoice updated', [
            'invoice_id' => $invoice->id,
            'new_amount_paid' => $newAmountPaid,
            'new_balance_due' => $newBalanceDue
        ]);

        // Mark invoice as completed if fully paid
        if ($newBalanceDue == 0) {
            $invoice->update([
                'invoice_status' => 'completed',
                'completed_at' => now(),
            ]);
            Log::info('Invoice marked as completed', ['invoice_id' => $invoice->id]);
        }

        // Update invoice services
        $invoiceService->updateGrandTotal($invoice, $transaction);
        $invoiceService->updateBalanceDue($invoice);
        $invoiceService->updateStatus($invoice);

        // Update transaction status if pending
        if ($transaction && $transaction->transaction_status === 'pending') {
            $transaction->update([
                'transaction_status' => 'receipt_verified',
                'updated_at' => now(),
            ]);
            Log::info('Transaction status updated', [
                'transaction_id' => $transaction->id,
                'new_status' => 'receipt_verified'
            ]);
        }

        // Validate user exists
        $user = $transaction->transactionUser;
        if (!$user) {
            throw new \Exception("No user associated with transaction ID {$transaction->id}");
        }

        // Apply payment to unpaid items
        $paymentService->applyPaymentToUnpaidItems($transaction, $payment->amount_paid);

        // Send confirmation email
        $this->sendPaymentConfirmationEmail($user, $transaction, $payment);

        Log::info('Successful payment processing completed', [
            'payment_id' => $payment->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * Send payment confirmation email
     */
    private function sendPaymentConfirmationEmail($user, $transaction, $payment): void
    {
        try {
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

                // branding details
                'branding_company_name' => $setting->company_name ?? 'Canopy Farm PH',
                'logo_path' => $setting->logo ?? '',
                'company_address' => $setting->address ?? '',
                'facebook_link' => $setting->facebook ?? '#',
                'instagram_link' => $setting->instagram ?? '#',
            ];

            Mail::to($paymentDetails['email'])->send(new PaymentUploadedMail($paymentDetails));

            Log::info('📧 Payment confirmation email sent', ['email' => $paymentDetails['email']]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
            // Don't throw - email failure shouldn't fail the entire webhook
        }
    }

    /**
     * Test endpoint to verify webhook is accessible
     */
    public function webhookTest()
    {
        return response()->json([
            'status' => 'active',
            'message' => 'Webhook endpoint is operational',
            'timestamp' => now()->toISOString()
        ], 200);
    }

    public function failed()
    {
        return view('guest.proof-of-payment-page');
    }
}
