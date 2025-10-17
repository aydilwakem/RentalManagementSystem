<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Transaction;
use App\Mail\ReceiptRejectedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    public function loadPaymentData(Payment $payment): array
    {
        $payment->loadMissing([
            'invoice.transaction',
            'paymentMethod',
        ]);

        if (!$payment->exists || !$payment->invoice || !$payment->invoice->transaction) {
            abort(404, 'Payment or related invoice/transaction not found.');
        }

        return [
            'payment' => $payment,
            'invoice' => $payment->invoice,
            'transaction' => $payment->invoice->transaction,
            'transactionUser' => $payment->invoice->transaction->transactionUser,
            'amount_paid' => $payment->amount_paid,
            'payment_type' => $payment->payment_type,
        ];
    }

    public function create(array $data)
    {
        Log::info('Creating payment via PaymentService.');

        $invoice = $data['invoice'];
        $transaction = $data['transaction'];

        // Create payment
        $payment = Payment::create([
            'invoice_id'               => $invoice->id,
            'payment_method_id'        => $data['payment_method_id'] ?? null,
            'mode_of_payment'          => $data['mode_of_payment'] ?? 'cash',
            'amount_paid'              => $data['amount_paid'] ?? 0,
            'convenience_fee'          => $data['convenience_fee'] ?? 0,
            'payment_type'             => $data['payment_type'] ?? null,
            'payment_date'             => $data['payment_date'] ?? now(),
            'payment_screenshot'       => $data['payment_screenshot'] ?? null,
            'payment_reference_number' => $data['payment_reference_number'] ?? null,
            'payment_status'           => $data['payment_status'] ?? 'pending',
            'rejection_reason'         => $data['rejection_reason'] ?? null,
            'notes'                    => $data['notes'] ?? null,
            'currency'                 => $data['currency'] ?? 'PHP',
            'verified_at'              => $data['verified_at'] ?? null,
            'refunded'                 => $data['refunded'] ?? false,
            'refunded_reason'          => $data['refunded_reason'] ?? null,
            'refunded_at'              => $data['refunded_at'] ?? null,
            'refunded_amount'          => $data['refunded_amount'] ?? 0,
        ]);

        // Only update invoice totals if payment is completed
        if (($data['payment_status'] ?? 'pending') === 'completed') {
            $totalPaid = $invoice->amount_paid + ($data['amount_paid'] ?? 0);
            $newBalance = max($invoice->sub_total - $totalPaid, 0);

            $invoice->update([
                'amount_paid' => $totalPaid,
                'balance_due' => $newBalance,
            ]);

            // Mark invoice status
            if ($newBalance === 0) {
                $invoice->update([
                    'invoice_status' => 'completed',
                    'completed_at'   => now(),
                ]);
            } elseif ($totalPaid > 0 && $newBalance > 0) {
                $invoice->update([
                    'invoice_status' => 'pending',
                ]);
            } else {
                $invoice->update([
                    'invoice_status' => 'pending',
                ]);
            }


            // Update transaction status if applicable
            if (
                in_array($transaction->transaction_status, ['pending', 'reserved']) &&
                $totalPaid > 0
            ) {
                $transaction->update(['transaction_status' => 'receipt_verified']);
            }
        }

        return $payment;
    }

    public function confirmUploadedPaymentReceipt(Payment $payment, float $amountPaid, string $paymentType): void
    {
        $invoice = $payment->invoice;
        $transaction = $invoice->transaction;

        if (!$invoice || !$transaction) {
            throw new \Exception("Invoice or transaction missing.");
        }

        $payment->update([
            'amount_paid' => $amountPaid, // 500
            'payment_type' => $paymentType,
            'payment_status' => 'completed',
            'verified_at' => now(),
        ]);

        $newAmountPaid = $invoice->amount_paid + $amountPaid; // 0 + 500


        $newBalanceDue = max($invoice->sub_total - $newAmountPaid, 0); // 1300 - 500 = 800

        $invoice->update([
            'amount_paid' => $newAmountPaid, // 500
            'balance_due' => $newBalanceDue, // 800
        ]);

        // Mark invoice status
        if ($newBalanceDue === 0) {
            $invoice->update([
                'invoice_status' => 'completed',
                'completed_at' => now(),
            ]);
        } elseif ($newAmountPaid > 0 && $newBalanceDue > 0) {
            $invoice->update([
                'invoice_status' => 'pending',
            ]);
        } else {
            $invoice->update([
                'invoice_status' => 'pending',
            ]);
        }

        if ($transaction->transaction_status === 'reserved') {
            $transaction->update([
                'transaction_status' => 'receipt_verified',
                'updated_at' => now(),
            ]);
        }
    }

    public function rejectUploadedPaymentReceipt(Payment $payment, string $reason, $user, BrandingService $brandingService = null): void
    {
        $payment->update([
            'payment_status' => 'failed',
            'rejection_reason' => $reason,
        ]);

        // Use provided branding service or create new instance
        $brandingService = $brandingService ?? app(BrandingService::class);
        $brandingData = $brandingService->getBrandingData();

        Mail::to($user->email)->send(new ReceiptRejectedMail([
            'rejection_reason' => $reason,
            'user_email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'facebook_link' => $brandingData['facebook_link'],
            'instagram_link' => $brandingData['instagram_link'],
        ]));
    }

    public function applyPaymentToUnpaidItems(Transaction $transaction, float $amountPaid): void
    {
        $remaining = $amountPaid;

        $tables = [
            ['name' => 'transaction_properties', 'amount_field' => 'total_amount'],
            ['name' => 'transaction_activities', 'amount_field' => 'amount'],
            ['name' => 'transaction_services', 'amount_field' => 'amount'],
        ];

        foreach ($tables as $table) {
            $items = DB::table($table['name'])
                ->where('transaction_id', $transaction->id)
                ->whereIn('payment_status', ['unpaid', 'partial'])
                ->orderBy('created_at')
                ->get();

            foreach ($items as $item) {

                $itemAmount = (float) $item->{$table['amount_field']};
                $paidAmount = (float) ($item->paid_amount ?? 0);
                $unpaidAmount = $itemAmount - $paidAmount;

                if ($unpaidAmount <= 0) {
                    continue;
                }

                if ($remaining >= $unpaidAmount) {
                    // Fully pay the item
                    DB::table($table['name'])
                        ->where('id', $item->id)
                        ->update([
                            'paid_amount' => $itemAmount,
                            'payment_status' => 'paid',
                            'updated_at' => now(),
                        ]);
                    $remaining -= $unpaidAmount;
                } elseif ($remaining > 0) {
                    // Partial payment
                    DB::table($table['name'])
                        ->where('id', $item->id)
                        ->update([
                            'paid_amount' => $paidAmount + $remaining,
                            'payment_status' => 'partial',
                            'updated_at' => now(),
                        ]);
                    $remaining = 0;
                    break;
                } else {
                    break;
                }
            }

            if ($remaining <= 0) {
                break;
            }
        }

        Log::info("₱{$amountPaid} applied to transaction {$transaction->id}. Remaining: ₱{$remaining}");
    }
}
