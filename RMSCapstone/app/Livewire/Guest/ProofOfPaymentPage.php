<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentUploadedMail;
use Illuminate\Support\Facades\Log;


class ProofOfPaymentPage extends Component
{

    use WithFileUploads;
    public $payment_methods;
    public $payment_method_id;
    public $transaction_id;
    public $payment_reference_number;
    public $payment_screenshot;
    public $notes;
    public $currency = 'PHP';
    public $transaction_number;

    public $transactionExpired = false;
    public $transactionNotFound = false;
    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }


    public function render()
    {
        return view('livewire.guest.proof-of-payment-page', [
            'payment_methods' => $this->payment_methods,
        ]);
    }

    public function mount()
    {
        Log::info('Mount is called.');
        $this->payment_methods = PaymentMethod::all();
    }

    public function updatedTransactionNumber()
    {
        // Reset flags
        $this->transactionExpired = false;
        $this->transactionNotFound = false;

        // Check if the transaction exists using transaction_number
        $transaction = Transaction::where('transaction_number', $this->transaction_number)->first();

        if (!$transaction) {
            // If the transaction does not exist
            $this->transactionNotFound = true;
            Log::info('Transaction not found for transaction number: ' . $this->transaction_number);
        } elseif ($transaction->transaction_status === 'expired') {
            // If the transaction exists but is expired, set the expired flag
            $this->transactionExpired = true;
        }
    }


    public function submitProofOfPayment()
    {
        if ($this->transactionExpired) {
            session()->flash('error', 'Your transaction has expired. You cannot upload proof of payment.');
            return;
        }

        // Validate input
        try {
            $this->validate([
                'payment_method_id' => 'required|exists:pm_payment_methods,id',
                'transaction_number' => 'required|exists:trn_transactions,transaction_number',
                'payment_reference_number' => 'required|string|max:255',
                'payment_screenshot' => 'required|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        $paymentDetails = [];

        DB::transaction(function () use (&$paymentDetails) {
            // Handle image upload
            if (!$this->payment_screenshot || !$this->payment_screenshot->isValid()) {
                throw new \Exception('Image upload failed. Please try again.');
            }

            $screenshotPath = $this->payment_screenshot->store('proof-of-payments', 'public');

            // Fetch transaction with relationships
            $transaction = Transaction::with(['invoice', 'transactionUser'])
                ->where('transaction_number', $this->transaction_number)
                ->firstOrFail();

            // Ensure invoice exists
            $invoice = $transaction->invoice;
            if (!$invoice) {
                throw new \Exception('Invoice not found for this transaction.');
            }

            // Create payment record
            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_method_id' => $this->payment_method_id,
                'amount_paid' => 0,
                'payment_screenshot' => $screenshotPath,
                'payment_reference_number' => $this->payment_reference_number,
                'payment_status' => 'pending',
                'notes' => $this->notes,
                'currency' => $this->currency,
            ]);

            // Update transaction status if needed
            if ($transaction->transaction_status === 'pending') {
                $transaction->update(['transaction_status' => 'reserved']);
            }

            // Prepare payment details for email
            $user = $transaction->transactionUser;
            $paymentDetails = [
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'payment_method_id' => $this->payment_method_id,
                'transaction_id' => $transaction->id,
                'payment_reference_number' => $this->payment_reference_number,
                'screenshot_path' => $screenshotPath,
                'notes' => $this->notes,
                'check_in' => $transaction->start_datetime,
                'check_out' => $transaction->end_datetime,
                'total_amount' => $transaction->total_amount,
                'deposit' => $transaction->deposit_amount,
            ];
        });

        // Reset form inputs
        $this->reset([
            'payment_method_id',
            'transaction_id',
            'payment_reference_number',
            'payment_screenshot',
            'notes',
            'currency',
        ]);

        // Send confirmation email
        try {
            Mail::to($paymentDetails['email'])->send(new PaymentUploadedMail($paymentDetails));
        } catch (\Exception $e) {
            logger()->error('Email send failed: ' . $e->getMessage());
            session()->flash('error', 'Payment is saved, but payment upload email failed to send.');
        }

        // Notify user
        session()->flash('message', 'Payment submitted successfully!');
        return redirect()->route('guest.thank-you-page');
    }
}
