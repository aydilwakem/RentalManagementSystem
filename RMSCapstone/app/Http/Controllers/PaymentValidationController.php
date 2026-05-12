<?php
// app/Http/Controllers/PaymentValidationController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentValidationController extends Controller
{
    public function validate($transactionNumber)
    {
        $transaction = Transaction::where('transaction_number', $transactionNumber)->first();

        if (!$transaction) {
            return redirect()->route('guest.payment-expired')
                ->with('error', 'Transaction not found.');
        }

        // 🚫 Check if EXPIRED
        if ($transaction->transaction_status === 'expired') {
            Log::info('Expired transaction attempted to pay', [
                'transaction_number' => $transaction->transaction_number
            ]);

            return redirect()->route('guest.payment-expired')
                ->with('message', 'This reservation has already expired.');
        }

        // 🚫 Check if COMPLETED (paid na)
        if ($transaction->transaction_status === 'completed') {
            Log::info('Completed transaction attempted to pay again', [
                'transaction_number' => $transaction->transaction_number
            ]);

            return redirect()->route('guest.payment-already-paid')
                ->with('message', 'This reservation has already been paid.');
        }

        // 🚫 Check if CANCELLED
        if ($transaction->transaction_status === 'cancelled') {
            Log::info('Cancelled transaction attempted to pay', [
                'transaction_number' => $transaction->transaction_number
            ]);

            return redirect()->route('guest.payment-cancelled')
                ->with('message', 'This reservation was cancelled.');
        }

        // 🚫 Check if may PayMongo link
        if (!$transaction->paymongo_checkout_url) {
            Log::error('Transaction missing PayMongo URL', [
                'transaction_number' => $transaction->transaction_number
            ]);

            return redirect()->route('guest.payment-error')
                ->with('error', 'Payment link not available. Please contact support.');
        }

        // ✅ All good! Redirect to PayMongo
        Log::info('Valid transaction accessing payment', [
            'transaction_number' => $transaction->transaction_number,
            'status' => $transaction->transaction_status
        ]);

        return redirect()->away($transaction->paymongo_checkout_url);
    }
}
