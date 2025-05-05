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


    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }


    public function mount()
    {
        $this->payment_methods = PaymentMethod::all();
    }

    public function submitProofOfPayment()
    {
        try {
            // Step 1: Validate form input
            $this->validate([
                'payment_method_id' => 'required|exists:pm_payment_methods,id',
                'transaction_id' => 'required|exists:trn_transactions,id',
                'payment_reference_number' => 'required|string|max:255',
                'payment_screenshot' => 'required|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Step 2: Handle the payment and invoice update in a transaction
        DB::transaction(function () {

            // Step 1: Ensure image upload is complete before storing
            if ($this->payment_screenshot && !$this->payment_screenshot->isValid()) {
                session()->flash('error', 'Image upload failed. Please try again.');
                return;
            }

            // Step 2: Store the payment screenshot
            $screenshotPath = null;
            if ($this->payment_screenshot) {
                $screenshotPath = $this->payment_screenshot->store('proof-of-payments', 'public'); // Saves in storage/app/public/proof-of-payments
            }

            // Step 3: Check if the transaction exists
            $transaction = Transaction::findOrFail($this->transaction_id);

            if (!$transaction) {
                throw new \Exception('Transaction not found.');
            }

            // Get the related invoice of the transaction
            $invoice = Invoice::where('transaction_id', $transaction->id)->first();

            if (!$invoice) {
                throw new \Exception('Invoice not found for this transaction.');
            }


            // Step 4: Create payment record
            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_method_id' => $this->payment_method_id,
                'amount_paid' => 0, // This will be set by the admin
                // 'payment_type' => This will be set by the admin
                'payment_screenshot' => $screenshotPath,
                'payment_reference_number' => $this->payment_reference_number,
                // 'payment_date' => This will be set by the admin
                'payment_status' => 'pending',
                'notes' => $this->notes,
                'currency' => $this->currency,
                // 'paid_at' => This will be set by the admin
            ]);

            // Step 5: Update the transaction status
            $transaction->update([
                'transaction_status' => 'reserved',
            ]);
        });

        // Step 6: Reset form fields
        $this->reset([
            'payment_method_id',
            'transaction_id',
            'payment_reference_number',
            'payment_screenshot',
            'notes',
            'currency',
        ]);

        // Step 7: Notify user and redirect
        session()->flash('message', 'Payment submitted successfully!');
        return redirect()->route('guest.reservation-form');
    }



    public function render()
    {
        return view('livewire.guest.proof-of-payment-page', [
            'payment_methods' => $this->payment_methods,
        ]);
    }
}
