<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function create(array $data)
    {
        Log::info('Creating payment via PaymentService.');

        $invoice = $data['invoice'];
        $transaction = $data['transaction'];

        // Create payment
        Payment::create([
            'invoice_id'       => $invoice->id,
            'amount_paid'      => $data['amount_paid'],
            'mode_of_payment'  => 'cash',
            'payment_type'     => $data['payment_type'],
            'payment_date'     => $data['payment_date'],
            'payment_status'   => 'completed',
            'notes'            => $data['notes'],
            'currency'         => 'PHP',
            'verified_at'      => now(),
        ]);

        // Update invoice
        $newAmountPaid = $invoice->amount_paid + $data['amount_paid'];
        $newBalanceDue = max($invoice->sub_total - $newAmountPaid, 0);

        $invoice->update([
            'amount_paid'  => $newAmountPaid,
            'balance_due'  => $newBalanceDue,
        ]);

        if ($newBalanceDue === 0) {
            $invoice->update([
                'invoice_status' => 'completed',
                'completed_at'   => now(),
            ]);
        }

        // Update transaction status
        if (
            in_array($transaction->transaction_status, ['pending', 'reserved']) &&
            $newAmountPaid >= $transaction->deposit_amount
        ) {
            $transaction->update(['transaction_status' => 'receipt_verified']);
        }
    }
}
