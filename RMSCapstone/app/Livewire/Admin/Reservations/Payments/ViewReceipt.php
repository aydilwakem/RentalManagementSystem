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
use App\Services\BrandingService;


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
    public $sub_total;
    public $balance_due;

    public string $facebookLink;

    public string $instagramLink;
    protected ServiceBag $services;
    protected PaymentService $paymentService;
    protected InvoiceService $invoiceService;

    protected BrandingService $brandingService;
    // -------------------------------- RENDER ---------------------------- //
    public function render()
    {
        return view('livewire.admin.reservations.payments.view-receipt');
    }

    public function boot(ServiceBag $services)
    {
        $this->invoiceService = $services->invoiceService;
        $this->paymentService = $services->paymentService;
        $this->brandingService = $services->brandingService;
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
        // Only update discount and grand total, don't touch payment calculations
        $this->invoiceService->updateDiscountTotal($this->invoice, $this->transaction);
        $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);

        // Refresh the display values
        $this->refreshInvoice();
    }


    public function refreshInvoice()
    {
        $this->invoice = $this->invoice->fresh();
        $this->sub_total = $this->invoice->sub_total;
        $this->balance_due = $this->invoice->balance_due;
    }





    // Confirm Receipt
    public function confirmReceipt(PaymentService $paymentService)
    {
        try {
            $this->validate([
                'amount_paid' => 'required|numeric|min:10|max:1000000.00',
                'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance,Merchandise,Accommodation Fully Paid,Accommodation Downpayment,Accommodation Balance,Day Tour',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmReceiptItem = false;
            $this->setErrorBag($e->validator->getMessageBag());
            return;
        }

        try {
            $this->paymentService->confirmUploadedPaymentReceipt(
                $this->payment,
                (float) $this->amount_paid,
                $this->payment_type
            );
            $this->updatePaymentStatus($paymentService, $this->amount_paid);
        } catch (\Exception $e) {
            Log::error('Confirm Receipt Failed: ' . $e->getMessage());

            // show the real reason to the user
            session()->flash('error', $e->getMessage());

            $this->confirmReceiptItem = false;
            return;
        }

        $this->recalculateInvoice();
        $this->confirmReceiptItem = false;
    }



    public function rejectReceipt()
    {
        $this->validate([
            'rejection_reason' => 'required|in:Incomplete details,Invalid receipt,Mismatched amount,Duplicate payment,Suspicious activity,Other',
        ]);

        try {
            $this->paymentService->rejectUploadedPaymentReceipt(
                $this->payment,
                $this->rejection_reason,
                $this->transactionUser,
                $this->brandingService // Inject the service if you used the constructor approach
            );
        } catch (\Exception $e) {
            Log::error('Reject Receipt Failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to reject receipt.');
            $this->showRejectModal = false;
            return;
        }

        $this->showRejectModal = false;
    }

    public function updatePaymentStatus(PaymentService $paymentService,  float $amountPaid)
    {

        $paymentService->applyPaymentToUnpaidItems($this->transaction, $amountPaid);

        $this->transaction->load('activities', 'properties', 'services');
    }
}
