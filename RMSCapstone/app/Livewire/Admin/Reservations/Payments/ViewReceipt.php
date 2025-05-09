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
    public $payment_type;
    public $rejection_reason;
    public $confirmReceiptItem = false;
    public $showRejectModal = false;


    // -------------------------------- RENDER ---------------------------- //
    public function render()
    {
        return view('livewire.admin.reservations.payments.view-receipt');
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


    // Confirm Receipt
    public function confirmReceipt()
    {
        Log::info('Confirm Receipt method called.');

        try {
            // Validate form input
            $this->validate([
                'amount_paid' => 'required|numeric|min:100|max:1000000.00',
                'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit', // Validation for the enum
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmReceiptItem = false;
            throw $e;
        }

        $this->payment->update([
            'amount_paid' => $this->amount_paid,
            'payment_type' => $this->payment_type,
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

        // If the transaction status is set to 'reserved' update the transaction status to 'receipt_verified'
        // Else, don't change the status, it means that the deposit has already been paid
        if ($this->transaction && $this->transaction->transaction_status === 'reserved') {
            $this->transaction->update([
                'transaction_status' => 'receipt_verified',
                'updated_at' => now()
            ]);
        }



        $this->confirmReceiptItem = false;

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction]);
    }

    // Reject receipt 
    public function rejectReceipt()
    {
        try {
            // Validate form input
            $this->validate([
                'rejection_reason' => 'required|in:Incomplete details,Invalid receipt,Mismatched amount,Duplicate payment,Suspicious activity,Other', // Validation for the enum
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->showRejectModal = false;
            throw $e;
        }

        $this->payment->update([
            'payment_status' => 'failed',
            'rejection_reason' => $this->rejection_reason,
        ]);

        // Prepare the payment details to send in the email
        $paymentDetails = [
            'rejection_reason' => $this->rejection_reason,
            'user_email' => $this->transactionUser->email,
            'first_name' => $this->transactionUser->first_name,
            'last_name' => $this->transactionUser->last_name,
        ];

        // Log the payment details for email
        Log::info('Preparing to send rejection email', $paymentDetails);

        try {
            // Send the rejection email
            Mail::to($this->transactionUser->email)
                ->send(new ReceiptRejectedMail($paymentDetails));
        } catch (\Exception $e) {
            // Log the error if email fails to send
            Log::error('Error sending rejection email: ' . $e->getMessage());
            throw $e;
        }

        $this->showRejectModal = false;

        return redirect()->route('admin.view-reservation', ['transaction' => $this->transaction]);
    }
}
