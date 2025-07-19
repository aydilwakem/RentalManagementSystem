<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService
{
    public function generateReceipt(Invoice $invoice): Receipt|null
    {
        if (!$invoice || $invoice->invoice_status !== 'completed') {
            Log::warning("Attempt to generate receipt for unpaid or missing invoice ID: {$invoice->id}");
            return null;
        }

        $existing = Receipt::where('invoice_id', $invoice->id)->first();
        if ($existing) {
            Log::info("Receipt already exists for invoice ID: {$invoice->id}");
            return $existing;
        }

        $datePart = now()->format('Ymd');
        $lastReceipt = Receipt::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')->first();

        $newNumber = $lastReceipt
            ? str_pad(((int) substr($lastReceipt->receipt_number, -4)) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        $receiptNumber = "OR-{$datePart}-{$newNumber}";

        $receipt = Receipt::create([
            'invoice_id'      => $invoice->id,
            'receipt_number'  => $receiptNumber,
            'amount_received' => $invoice->amount_paid,
            'receipt_date'    => now(),
            'notes'           => 'Official receipt generated via system',
        ]);

        Log::info("Receipt created: {$receiptNumber} for Invoice ID {$invoice->id}");

        return $receipt;
    }

    public function generatePdf(array $data, string $receiptNumber)
    {
        return Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data)
            ->output();
    }
}
