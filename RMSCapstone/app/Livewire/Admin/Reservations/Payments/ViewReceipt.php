<?php

namespace App\Livewire\Admin\Reservations\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use App\Mail\ReceiptRejectedMail;
use Illuminate\Support\Facades\Mail;

#[Layout('layouts.app')]

class ViewReceipt extends Component
{

    public Payment $payment; // Holds the current transaction
    public $invoice;
    public $transaction;
    public $transactionUser;
    public $amount_paid;


    public $confirmReceiptItem = false;
    public $rejectReceiptItem = false;


    //Method to make modal true
    public function ConfirmReceiptModal()
    {
        $this->confirmReceiptItem = true;
    }

    public function RejectReceiptModal()
    {
        $this->rejectReceiptItem = true;
    }


    public function mount(Payment $payment)
    {
        // Method to load related Payment data
        $this->loadPaymentData($payment);
    }

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

        // Check if invoice or transaction is missing
        if (!$this->invoice || !$this->transaction) {
            abort(404, 'Invoice or transaction not found for the payment.');
        }

        // Set the amount paid
        $this->amount_paid = $payment->amount_paid;
    }


    public function render()
    {
        return view('livewire.admin.reservations.payments.view-receipt');
    }

    public function confirmReceipt()
    {


        Log::info('ConfirmReceipt method called.');

        try {
            // Validate form input
            $this->validate([
                'amount_paid' => 'required|numeric|min:100|max:1000000.00',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmReceiptItem = false;
            throw $e;
        }

        $this->payment->update([
            'amount_paid' => $this->amount_paid,
            'payment_status' => 'completed',
            'verified_at' => now(),
        ]);

        if ($this->invoice && $this->payment) {

            // Update the invoice amount_paid
            $newAmountPaid = $this->invoice->amount_paid + $this->payment->amount_paid;

            $this->invoice->update([
                'amount_paid' => $newAmountPaid, // 0 + 17050 = 17050  
                'balance_due' => $this->invoice->sub_total - $newAmountPaid, //34100 - 17050 = 17050
            ]);
        }

        if ($this->transaction && $this->transaction->transaction_status === 'reserved') {
            $this->transaction->update([
                'transaction_status' => 'receipt_verified'
            ]);
        }


        $this->confirmReceiptItem = false;

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction]);
    }


    public function rejectReceipt()
    {
        $this->payment->update([
            'payment_status' => 'failed',
        ]);

        // Email to send notification to user that their payment is rejected

        $this->rejectReceiptItem = false;

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction]);
    }
}
