<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function computeBaseSubtotal(Transaction $transaction): float
    {
        // Retrieves total amount of activities assigned to the transaction
        $activitiesTotal = $transaction->activities?->sum(
            fn($activity) => $activity->pivot->amount
        ) ?? 0;

        // Retrieves total amount of services assigned to the transaction
        $servicesTotal = $transaction->services?->sum(
            fn($service) => $service->pivot->amount
        ) ?? 0;

        // Retrieves total amount of rooms assigned to the transaction
        $roomsTotal = $transaction->properties?->sum(
            fn($property) => $property->pivot->total_amount
        ) ?? 0;

        $baseSubtotal = $activitiesTotal + $roomsTotal + $servicesTotal;
        $discount = $transaction->promo_discount_amount ?? 0;

        return max($baseSubtotal - $discount, 0);
    }

    public function updateGrandTotal(Invoice $invoice, Transaction $transaction): void
    {
        // Subtotal is the amount of all items (without the convenience fee)
        $subtotal = $this->computeBaseSubtotal($transaction);

        // Convenience Fee Total is the total of convenience fee of all payments assigned to the transaction
        $convenienceFeeTotal = $invoice->payments
            ?->where('payment_status', 'completed')
            ->sum('convenience_fee') ?? 0;

        // Use fallback from transaction if no successful payment fee found
        if ($convenienceFeeTotal == 0 && $transaction->convenience_fee > 0) {
            $convenienceFeeTotal = $transaction->convenience_fee;
        }

        // Grand total is equal to the subtotal and convenience fee total
        $grandTotal = $subtotal + $convenienceFeeTotal;

        $invoice->update([
            'sub_total' => $grandTotal // in the database it is saved as sub_total
        ]);
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
}
