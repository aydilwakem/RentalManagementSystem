<?php

namespace App\Livewire\Admin\Reservations\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use App\Mail\ReceiptRejectedMail;
use App\Services\PaymentService;
use App\Services\InvoiceService;
use App\Services\ServiceBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

#[Layout('layouts.app')]

class ViewReceipt extends Component
{

    public Payment $payment; // Holds the current transaction
    public $invoice;
    public $transaction;
    public $transactionUser;
    public $amount_paid;
    public $payment_type;
    public $rejection_reason;
    public $confirmReceiptItem = false;
    public $showRejectModal = false;

    protected ServiceBag $services;
    protected PaymentService $paymentService;
    protected InvoiceService $invoiceService;

    // -------------------------------- RENDER ---------------------------- //
    public function render()
    {
        return view('livewire.admin.reservations.payments.view-receipt');
    }

    public function boot(ServiceBag $services)
    {
        $this->invoiceService = $services->invoiceService;
        $this->paymentService = $services->paymentService;
    }

    // -------------------------------- MODALS --------------------------- //
    public function ConfirmReceiptModal()
    {
        $this->confirmReceiptItem = true;
    }

    // -------------------------------- MOUNT --------------------------- //
    public function mount(Payment $payment)
    {
        // Method to load related Payment data
        $this->loadPaymentData($payment);
    }

    // -------------------------------- METHODS --------------------------- //


    // Load payment data
    private function loadPaymentData(Payment $payment)
    {
        // Load the necessary relationships eagerly, only if they are not already loaded
        $payment->loadMissing([
            'invoice.transaction',
            'paymentMethod'
        ]);

        // Ensure the payment record exists (this check is redundant because the $payment is injected)
        if (!$payment->exists) {
            abort(404, 'Payment not found.');
        }

        // Assign payment-related data to class properties
        $this->payment = $payment;
        $this->invoice = $payment->invoice;
        $this->transaction = $this->invoice->transaction;
        $this->transactionUser = $this->transaction->transactionUser;

        // Check if invoice or transaction is missing
        if (!$this->invoice || !$this->transaction) {
            abort(404, 'Invoice or transaction not found for the payment.');
        }

        // Set the amount paid if payment already verified
        $this->amount_paid = $payment->amount_paid;
        $this->payment_type = $payment->payment_type;
    }


    public function recalculateInvoice()
    {
        $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);
        $this->invoiceService->updateBalanceDue($this->invoice);
        $this->invoiceService->updateStatus($this->invoice);
    }

    // Confirm Receipt
    public function confirmReceipt(PaymentService $paymentService)
    {
        $this->validate([
            'amount_paid' => 'required|numeric|min:0|max:1000000.00',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance',
        ]);

        try {
            $this->paymentService->confirmUploadedPaymentReceipt($this->payment, (float) $this->amount_paid, $this->payment_type);
            $this->updatePaymentStatus($paymentService);
        } catch (\Exception $e) {
            Log::error('Confirm Receipt Failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to confirm receipt.');
            return;
        }

        $this->recalculateInvoice();
        $this->confirmReceiptItem = false;

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction]);
    }


    // Reject receipt 
    public function rejectReceipt()
    {
        $this->validate([
            'rejection_reason' => 'required|in:Incomplete details,Invalid receipt,Mismatched amount,Duplicate payment,Suspicious activity,Other',
        ]);

        try {
            $this->paymentService->rejectUploadedPaymentReceipt($this->payment, $this->rejection_reason, $this->transactionUser);
        } catch (\Exception $e) {
            Log::error('Reject Receipt Failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to reject receipt.');
            return;
        }

        $this->showRejectModal = false;

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction]);
    }

    public function updatePaymentStatus(PaymentService $paymentService)
    {

        $paymentService->markAllUnpaidItemsAsPaid($this->transaction);

        $this->transaction->load('activities', 'properties', 'services');
    }
}
