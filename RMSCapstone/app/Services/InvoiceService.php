<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function updateGrandTotal(Invoice $invoice, Transaction $transaction): void
    {
        $activitiesTotal = $transaction->activities->sum(function ($activity) {
            return $activity->pivot->quantity * $activity->amount;
        });

        $servicesTotal = $transaction->services->sum(function ($service) {
            return $service->pivot->quantity * $service->amount;
        });

        $roomsTotal = $transaction->properties->sum(fn($property) => $property->pivot->total_amount);


        // Assume payments are already loaded via invoice
        $convenienceFeeTotal = $invoice->payments
            ->where('payment_status', 'completed')
            ->sum('convenience_fee');

        $subtotal = $activitiesTotal + $roomsTotal + $servicesTotal + $convenienceFeeTotal;

        $invoice->update(['sub_total' => $subtotal]);
    }

    public function computeBaseSubtotal(Transaction $transaction): float
    {
        $activities = $transaction->activities ?? collect();
        $properties = $transaction->properties ?? collect();
        $services = $transaction->services ?? collect();

        $activitiesTotal = $activities->map(
            fn($activity) =>
            $activity->pivot->quantity * $activity->amount
        )->sum();

        $servicesTotal = $services->map(
            fn($service) =>
            $service->pivot->quantity * $service->amount
        )->sum();

        $roomsTotal = $properties->map(
            fn($property) =>
            $property->pivot->total_amount
        )->sum();

        return $activitiesTotal + $roomsTotal + $servicesTotal;
    }

    public function updateBalanceDue(Invoice $invoice): void
    {
        $totalPaid = $invoice->payments
            ->where('payment_status', 'completed')
            ->sum('amount_paid');

        $balanceDue = max($invoice->sub_total - $totalPaid, 0);

        $invoice->update([
            'balance_due' => $balanceDue,
            'amount_paid' => $totalPaid,
        ]);
    }

    public function updateStatus(Invoice $invoice): void
    {
        if ($invoice->balance_due <= 0) {
            if (!$invoice->completed_at) {
                $invoice->update([
                    'invoice_status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        } elseif ($invoice->balance_due > 0 && $invoice->sub_total > 0) {
            $invoice->update([
                'invoice_status' => 'pending',
                'completed_at' => null,
            ]);
        } else {
            $invoice->update([
                'invoice_status' => 'failed',
                'completed_at' => null,
            ]);
        }
    }

    public function markTransactionItemsAsPaid(Transaction $transaction): void
    {
        DB::table('transaction_activities')
            ->where('transaction_id', $transaction->id)
            ->where('payment_status', 'unpaid')
            ->update([
                'payment_status' => 'paid',
                'updated_at' => now(),
            ]);

        DB::table('transaction_properties')
            ->where('transaction_id', $transaction->id)
            ->where('payment_status', 'unpaid')
            ->update([
                'payment_status' => 'paid',
                'updated_at' => now(),
            ]);

        DB::table('transaction_services')
            ->where('transaction_id', $transaction->id)
            ->where('payment_status', 'unpaid')
            ->update([
                'payment_status' => 'paid',
                'updated_at' => now(),
            ]);

        Log::info("All unpaid items for transaction {$transaction->id} marked as paid.");
    }
}
