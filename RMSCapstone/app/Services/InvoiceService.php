<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\InvoiceDiscount;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function computeBaseSubtotal(Transaction $transaction): float
    {
        $activitiesTotal = $transaction->activities?->sum(
            fn($activity) => $activity->pivot->amount
        ) ?? 0;

        $servicesTotal = $transaction->services?->sum(
            fn($service) => $service->pivot->amount
        ) ?? 0;

        $roomsTotal = $transaction->properties?->sum(
            fn($property) => $property->pivot->total_amount
        ) ?? 0;

        // Base subtotal = items + convenience fee (fixed)
        return $activitiesTotal + $servicesTotal + $roomsTotal;
    }


    public function updateGrandTotal(Invoice $invoice, Transaction $transaction): void
    {
        $baseSubtotal = $this->computeBaseSubtotal($transaction);

        // Discounts (promo, PWD, senior, etc.)
        $totalDiscount = $this->updateDiscountTotal($invoice, $transaction);
        $promoDiscount = $transaction->promo_discount_amount ?? 0;

        // Get convenience fee directly from payments
        $convenienceFee = $invoice->payments()->sum('convenience_fee');

        // Apply discounts first, then add convenience fee
        $subTotal = max($baseSubtotal - $totalDiscount - $promoDiscount + $convenienceFee, 0);

        $invoice->update([
            'base_subtotal'   => $baseSubtotal,
            'sub_total'       => $subTotal,
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

    public function updateDiscountTotal(Invoice $invoice, Transaction $transaction)
    {
        $totalDiscount = 0;

        foreach ($invoice->discounts as $discount) {
            switch ($discount->discount_type) {
                case 'PWD':
                    // Apply only to eligible items (e.g., rooms)
                    $eligibleAmount = $transaction->properties->sum(
                        fn($p) => $p->pivot->total_amount
                    );
                    $totalDiscount += $eligibleAmount * ($discount->discount_value / 100);
                    break;

                case 'Senior':
                    $eligibleAmount = $transaction->services->sum(
                        fn($s) => $s->pivot->amount
                    );
                    $totalDiscount += $eligibleAmount * ($discount->discount_value / 100);
                    break;

                default:
                    // Fixed amount discount
                    $totalDiscount += $discount->discount_value;
            }
        }

        $invoice->update([
            'total_discount' => $totalDiscount,
        ]);

        return $totalDiscount; // optionally return for use in updateGrandTotal
    }
}
