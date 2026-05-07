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
    public Payment $payment;
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

    public function ConfirmReceiptModal()
    {
        $this->confirmReceiptItem = true;
    }

    public function mount(Payment $payment)
    {
        $this->loadPaymentData($payment);
    }

    private function loadPaymentData(Payment $payment)
    {
        $payment->loadMissing([
            'invoice.transaction',
            'paymentMethod'
        ]);

        if (!$payment->exists) {
            abort(404, 'Payment not found.');
        }

        $this->payment = $payment;
        $this->invoice = $payment->invoice;
        $this->transaction = $this->invoice->transaction;
        $this->transactionUser = $this->transaction->transactionUser;

        if (!$this->invoice || !$this->transaction) {
            abort(404, 'Invoice or transaction not found for the payment.');
        }

        $this->amount_paid = $payment->amount_paid;
        $this->payment_type = $payment->payment_type;
    }

    public function recalculateInvoice()
    {
        // For day tours, use manual recalculation to avoid service conflicts
        if ($this->isDayTourTransaction()) {
            $this->manualDayTourInvoiceRecalculation();
        } else {
            // Use existing service for other transaction types
            $this->invoiceService->updateDiscountTotal($this->invoice, $this->transaction);
            $this->invoiceService->updateGrandTotal($this->invoice, $this->transaction);
        }

        $this->refreshInvoice();
    }

/**
 * Manual invoice recalculation specifically for day tours
 */
protected function manualDayTourInvoiceRecalculation()
{
    // Refresh to get latest data
    $this->invoice->refresh();
    
    $baseSubtotal = $this->transaction->sub_total;
    $totalDiscount = $this->invoice->discounts->sum('discount_value') ?? 0;
    $convenienceFee = $this->invoice->payments()->where('payment_status', 'completed')->sum('convenience_fee');
    
    // Calculate total paid from completed payments
    $totalPaid = $this->invoice->payments()
        ->where('payment_status', 'completed')
        ->sum('amount_paid');

    $grandTotal = max(($baseSubtotal - $totalDiscount) + $convenienceFee, 0);
    $balanceDue = max($grandTotal - $totalPaid, 0);

    // Update the invoice
    $this->invoice->update([
        'base_subtotal' => $baseSubtotal,
        'total_discount' => $totalDiscount,
        'sub_total' => $grandTotal,
        'amount_paid' => $totalPaid,
        'balance_due' => $balanceDue,
    ]);

    Log::info("Day tour invoice recalculated: Subtotal: ₱{$baseSubtotal}, Discount: ₱{$totalDiscount}, Convenience: ₱{$convenienceFee}, Grand Total: ₱{$grandTotal}, Paid: ₱{$totalPaid}, Balance: ₱{$balanceDue}");
}

    public function refreshInvoice()
    {
        $this->invoice = $this->invoice->fresh();
        $this->sub_total = $this->invoice->sub_total;
        $this->balance_due = $this->invoice->balance_due;
    }

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
            if ($this->isDayTourTransaction()) {
                $this->confirmDayTourPaymentReceipt((float) $this->amount_paid, $this->payment_type);
            } else {
                // Use existing service for other transaction types
                $this->paymentService->confirmUploadedPaymentReceipt(
                    $this->payment,
                    (float) $this->amount_paid,
                    $this->payment_type
                );
                $this->updatePaymentStatus($paymentService, $this->amount_paid);
            }

            // Recalculate invoice
            $this->recalculateInvoice();

        } catch (\Exception $e) {
            Log::error('Confirm Receipt Failed: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            $this->confirmReceiptItem = false;
            return;
        }

        $this->confirmReceiptItem = false;
    }

    /**
     * Custom payment confirmation for day tours to prevent overpayment
     */
    protected function confirmDayTourPaymentReceipt(float $amountPaid, string $paymentType): void
    {
        $invoice = $this->invoice;
        $transaction = $this->transaction;

        if (!$invoice || !$transaction) {
            throw new \Exception("Invoice or transaction missing.");
        }

        // Update the payment record
        $this->payment->update([
            'amount_paid' => $amountPaid,
            'payment_type' => $paymentType,
            'payment_status' => 'completed',
            'verified_at' => now(),
        ]);

        // Refresh to get current data
        $invoice->refresh();

        // Calculate total paid from ALL completed payments (this is the fix)
        $totalPaid = $invoice->payments()
            ->where('payment_status', 'completed')
            ->sum('amount_paid');

        // Calculate new balance
        $newBalanceDue = max($invoice->sub_total - $totalPaid, 0);

        // Update invoice
        $invoice->update([
            'amount_paid' => $totalPaid,
            'balance_due' => $newBalanceDue,
        ]);

        // Update invoice status
        if ($newBalanceDue === 0) {
            $invoice->update([
                'invoice_status' => 'completed',
                'completed_at' => now(),
            ]);
        } elseif ($totalPaid > 0 && $newBalanceDue > 0) {
            $invoice->update([
                'invoice_status' => 'pending',
            ]);
        }

        // Update transaction status if needed
        if ($transaction->transaction_status === 'reserved') {
            $transaction->update([
                'transaction_status' => 'receipt_verified',
                'updated_at' => now(),
            ]);
        }

        Log::info("Day tour payment confirmed: ₱{$amountPaid} for invoice {$invoice->id}. Total paid: ₱{$totalPaid}, Balance: ₱{$newBalanceDue}");
    }


    /**
     * Special payment status update for day tours to prevent overpayment
     */
    protected function updateDayTourPaymentStatus(float $amountPaid)
    {
        // For day tours, we don't need to apply payment to items since it's a single package
        // Just update the transaction status if needed
        if ($this->transaction->transaction_status === 'reserved') {
            $this->transaction->update([
                'transaction_status' => 'receipt_verified',
                'updated_at' => now(),
            ]);
        }

        Log::info("Day tour payment applied: ₱{$amountPaid} for transaction {$this->transaction->id}");
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
                $this->brandingService
            );
        } catch (\Exception $e) {
            Log::error('Reject Receipt Failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to reject receipt.');
            $this->showRejectModal = false;
            return;
        }

        $this->showRejectModal = false;
    }

    public function updatePaymentStatus(PaymentService $paymentService, float $amountPaid)
    {
        $paymentService->applyPaymentToUnpaidItems($this->transaction, $amountPaid);
        $this->transaction->load('activities', 'properties', 'services');
    }

    /**
     * Check if this is a day tour transaction
     */
    protected function isDayTourTransaction(): bool
    {
        return $this->transaction->transaction_type === 'daytour' || 
               !empty($this->transaction->day_tour_id) ||
               $this->transaction->dayTour !== null;
    }

}