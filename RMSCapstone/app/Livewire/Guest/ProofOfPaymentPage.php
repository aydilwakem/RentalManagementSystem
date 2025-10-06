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
use App\Services\PaymentService;
use App\Services\EmailService;



class ProofOfPaymentPage extends Component
{

    use WithFileUploads;
    public $payment_methods;
    public $payment_method_id;

    public $payment_type;
    public $transaction_id;
    public $payment_reference_number;
    public $payment_screenshot;
    public $notes;
    public $currency = 'PHP';
    public $transaction_number;

    public $transactionExpired = false;
    public $transactionNotFound = false;
    public $confirmCreateItem = false;
    protected PaymentService $paymentService;
    protected EmailService $emailService;

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
        $this->payment_methods = PaymentMethod::where('mode_of_payment_name', '!=', 'Cash')->get();
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

    protected function validateInput(): void
    {
        $this->validate([
            'payment_method_id' => 'required|exists:pm_payment_methods,id',
            'transaction_number' => 'required|exists:trn_transactions,transaction_number',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance,Merchandise,Accommodation Fully Paid,Accommodation Downpayment,Accommodation Balance',
            'payment_reference_number' => 'required|string|max:255|not_regex:/[<>?!@#$]/',
            'payment_screenshot' => 'required|image|max:2048',
            'notes' => 'nullable|string|max:255|not_regex:/[<>?!@#$]/',
        ]);
    }

    protected function uploadScreenshot(): string
    {
        // Error handling of failed upload
        if (!$this->payment_screenshot || !$this->payment_screenshot->isValid()) {
            throw new \Exception('Image upload failed. Please try again.');
        }

        // Returns the screenshot path where the screenshot is saved.
        return $this->payment_screenshot->store('proof-of-payments', 'public');
    }

    protected function getTransactionWithRelations()
    {
        return Transaction::with(['invoice', 'transactionUser'])
            ->where('transaction_number', $this->transaction_number)
            ->firstOrFail();
    }

    protected function prepareEmailData($transaction, string $screenshotPath): array
    {
        $user = $transaction->transactionUser;

        return [
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
    }

    protected function resetInputFields(): void
    {
        $this->reset([
            'payment_method_id',
            'transaction_id',
            'payment_reference_number',
            'payment_screenshot',
            'notes',
            'currency',
        ]);
    }

    public function submitProofOfPayment(PaymentService $paymentService, EmailService $emailService)
    {
        // Declines payment if the transaction is marked as 'expired'.
        if ($this->transactionExpired) {
            session()->flash('error', 'Your transaction has expired. You cannot upload proof of payment.');
            return;
        }

        try {

            // Validate user inputs
            $this->validateInput();

            // Stores payment details
            $paymentDetails = [];

            DB::transaction(function () use (&$paymentDetails, $paymentService) {

                // Gets the screenshotpath of the image
                $screenshotPath = $this->uploadScreenshot();

                // Get transaction relations: transaction->invoice
                $transaction = $this->getTransactionWithRelations();
                $invoice = $transaction->invoice;

                // Error handling for non-existing invoice
                if (!$invoice) {
                    throw new \Exception('Invoice not found for this transaction.');
                }

                // Creates payment usint the PaymentService class (create)
                $paymentService->create([
                    'invoice' => $invoice,
                    'transaction' => $transaction,
                    'payment_method_id' => $this->payment_method_id,
                    'payment_reference_number' => $this->payment_reference_number,
                    'payment_screenshot' => $screenshotPath,
                    'payment_type' => $this->payment_type,
                    'payment_status' => 'pending',
                    'currency' => $this->currency,
                    'notes' => $this->notes,
                    'mode_of_payment' => 'manual_upload',
                    'amount_paid' => 0,
                ]);

                // Updates transaction status if pending
                if ($transaction->transaction_status === 'pending') {
                    $transaction->update(['transaction_status' => 'reserved']);
                }

                // Prepares the email data
                $paymentDetails = $this->prepareEmailData($transaction, $screenshotPath);
            });

            // resetInputFields
            $this->resetInputFields();

            // Send confirmation email using EmailService class (sendPaymentUploadedMail)
            try {
                $emailService->sendPaymentUploadedMail($paymentDetails['email'], $paymentDetails);
            } catch (\Exception $e) {
                logger()->error('Email send failed: ' . $e->getMessage());
                session()->flash('error', 'Payment saved, but email failed to send.');
            }

            session()->flash('message', 'Payment submitted successfully!');
            return redirect()->route('guest.thank-you-page');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        } catch (\Exception $e) {
            logger()->error('Payment submission failed: ' . $e->getMessage());
            session()->flash('error', 'An error occurred during payment submission.');
        }
    }
}
