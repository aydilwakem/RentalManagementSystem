<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Receipt;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfficialReceiptMail;

#[Layout('layouts.app')]
class ViewReservation extends Component
{
    public $transaction; // Holds the current transaction
    public $transactionUser; // Holds the transaction user 
    public $invoice; // Holds the invoice associated with the transaction
    public $guestDetails; // Holds all guest associated with the transaction
    public $activities; // Holds all activities associated with the transaction
    public $properties; // Holds all properties associated with the transaction
    public $payments; // Holds all payments associated with the invoice
    public $totalAddons; // Holds the total amount of addons
    public $totalRooms; // Holds the total amount of rooms
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;
    public $receipt;
    public $receiptNumber;

    public function render()
    {
        $this->activities = $this->transaction->activities()->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $this->transaction->properties()->withPivot('adults', 'kids', 'extra_guest', 'extra_charge', 'amount', 'total_amount', 'days')->get();


        return view('livewire.admin.reservations.view-reservation', [
            'activities' => $this->activities,  // Pass activities to the view properly
            'properties' => $this->properties,  // Pass activities to the view properly
        ]);
    }

    public function mount(Transaction $transaction)
    {
        $this->loadTransactionData($transaction);
    }

    public function loadTransactionData(Transaction $transaction)
    {
        // Eager-load related models to avoid N+1 query problem.
        // This loads relationships only if they haven't already been loaded.
        $transaction->loadMissing([
            'invoice.payments',     // Load the invoice and its related payments
            'transactionUser',      // Load the user related to the transaction
            'guestDetails',         // Load additional guest details associated with the transaction
            'properties',           // Load the properties (e.g., rooms) included in the transaction
            'activities',           // Load activities (e.g., addons or services) related to the transaction
        ]);

        // If there's no invoice associated with the transaction, abort and return a 404 error.
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Assign the loaded models to the component's public properties for use in the Blade view
        $this->transaction = $transaction;
        $this->transactionUser = $transaction->transactionUser;                // Store the full transaction
        $this->invoice = $transaction->invoice;            // Store the invoice details
        $this->guestDetails = $transaction->guestDetails;  // Store guest details for display
        $this->activities = $transaction->activities;      // Store activities (addons/services)
        $this->properties = $transaction->properties;      // Store properties (rooms)

        // Store payment records from the invoice, or an empty collection if none
        $this->payments = $this->invoice->payments ?? collect();

        // Get computed totals from model accessors (defined in the Transaction model)
        $this->totalRooms = $transaction->total_rooms;     // Total cost from rooms (via accessor)
        $this->totalAddons = $transaction->total_addons;   // Total cost from addons (via accessor)
    }

    public function GenerateReceipt()
    {
        Log::info('Show Generate Official Receipt Modal method triggered.');

        // Ensure invoice is available
        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        // Prevent generating receipt for unpaid invoices
        if ($this->invoice->invoice_status != 'completed') {
            $this->cannotGenerateReceiptModal = true;
            Log::warning("Attempt to generate receipt for unpaid invoice ID: {$this->invoice->id}");
            return; // ⛔️ stop further execution
        }

        // Check if receipt already exists
        $existing = Receipt::where('invoice_id', $this->invoice->id)->first();
        if ($existing) {
            $this->receipt = $existing;
            Log::info('Receipt already exists for invoice ID: ' . $this->invoice->id);
        } else {
            // Generate unique receipt number
            $datePart = now()->format('Ymd');
            $lastReceipt = Receipt::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')->first();

            $newNumber = $lastReceipt
                ? str_pad(((int) substr($lastReceipt->receipt_number, -4)) + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $receiptNumber = "OR-{$datePart}-{$newNumber}";

            $this->receipt = Receipt::create([
                'invoice_id'      => $this->invoice->id,
                'receipt_number'  => $receiptNumber,
                'amount_received' => $this->invoice->amount_paid,
                'receipt_date'    => now(),
                'notes'           => 'Official receipt generated via system',
            ]);

            Log::info("Receipt created: {$receiptNumber} for Invoice ID {$this->invoice->id}");
        }
    }

    public function ShowReceipt()
    {
        Log::info('Show Receipt method called.');

        if (!$this->invoice) {
            abort(404, 'No invoice found. Please reload the page.');
        }

        if ($this->invoice->balance_due > 0) {
            abort(400, 'Receipt cannot be shown. Invoice still has balance due.');
        }

        $existing = Receipt::where('invoice_id', $this->invoice->id)->first();

        if (!$existing) {
            abort(404, 'No receipt found for this invoice.');
        }

        $this->receipt = $existing;
        $this->showReceiptModal = true;
    }

    public function printOfficialReceipt()
    {
        Log::info('Print Official Receipt method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            abort(404, 'Missing data for generating the official receipt.');
        }

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
        ];

        $pdf = Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'official_receipt_' . $this->receipt->receipt_number . '.pdf');
    }

    public function sendReceiptToEmail()
    {
        Log::info('Send Receipt To Email Method called.');

        if (!$this->receipt || !$this->invoice || !$this->transaction) {
            abort(404, 'Missing data for sending the official receipt.');
        }

        $data = [
            'receipt' => $this->receipt,
            'invoice' => $this->invoice,
            'transaction' => $this->transaction,
        ];

        // Generate PDF in memory
        $pdf = Pdf::loadView('admin.pdf.reservations.receipts.officialReceipt', $data);
        $pdfContent = $pdf->output();

        // Send the email with attachment
        Mail::to($this->transaction->transactionUser->email)->send(new SendOfficialReceiptMail($pdfContent, $this->receipt->receipt_number));
    }

    public function exportReservationDetails()
    {
        $transaction = Transaction::with([
            'invoice.payments',
            'transactionUser',
            'guestDetails',
            'properties',
            'activities' => function ($query) {
                $query->withPivot('quantity', 'amount', 'activity_datetime', 'status');
            },
        ])->findOrFail($this->transaction->id);

        $pdf = Pdf::loadView('livewire.admin.reservations.reservation-details', [
            'transaction' => $transaction,  // Pass the actual transaction
            //Pass the relationships
            'guestDetails' => $transaction->guestDetails,
            'invoice' => $transaction->invoice,
            'activities' => $transaction->activities,
            'properties' => $transaction->properties,
            'payments' => $transaction->invoice->payments,
            'totalRooms' => $transaction->totalRooms,
            'totalAddons' => $transaction->totalAddons,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reservation-details-' . $this->transaction->start_datetime . '.pdf');
    }
}
