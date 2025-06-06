<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

#[Layout('layouts.app')]
class ViewLease extends Component
{
    public Transaction $transaction;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public $invoice;
    public $payments;

    // ---------------- PAYMENT RELATED PROPERTIES ------------------ //

    public $invoice_id;
    public $amount_paid;
    public $mode_of_payment;
    public $payment_type;
    public $payment_date;
    public $payment_status;
    public $notes;
    public $currency;
    public $verified_at;

    // ---------------------------- MODALS -------------------------- // 
    public $showReceiptModal = false;
    public $cannotGenerateReceiptModal = false;
    public $createPaymentModal = false;


    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
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
        ]);

        // If there's no invoice associated with the transaction, abort and return a 404 error.
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Assign the loaded models to the component's public properties for use in the Blade view
        $this->transaction = $transaction;
        $this->invoice = $transaction->invoice;            // Store the invoice details
        // Store payment records from the invoice, or an empty collection if none
        $this->payments = $this->invoice->payments ?? collect();
    }

    public function deleteLease(Transaction $transaction)
    {
        if (!$transaction) {
            session()->flash('error', 'Lease not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            if (in_array($transaction->transaction_status, ['done', 'terminated'])) {
                $transaction->delete();
                $this->confirmItemDelete = false;

                session()->flash('message', 'Lease successfully deleted!');
                return redirect()->route('admin.events');
            } else {
                // Set modal flag if event is not deletable
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = false;
            }
        }
    }


    public function exportLeaseDetails()
    {
        $pdf = Pdf::loadView('livewire.admin.properties.leases.lease-details', [
            'transaction' => $this->transaction,  // Pass the actual lease
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'lease-details-' . $this->transaction->start_datetime . '.pdf');
    }

    public function getMonthCount($startDatetime, $endDatetime)
    {
        //parse the end and start date 
        $start = Carbon::parse($startDatetime);
        $end = Carbon::parse($endDatetime);

        //get the number of months in between
        $months = $start->diffInMonths($end);

        //if the start and end are in the same month, we count it as 1
        if ($start->isSameMonth($end)) {
            return 1;
        }

        //add 1 to include the starting month
        return $months + 1;
    }


    public function render()
    {
        return view('livewire.admin.properties.leases.view-lease');
    }


    public function OpenCreatePaymentModal()
    {

        Log::info('Open Create Payment method called.');
        $this->createPaymentModal = true;
    }

    public function CloseCreatePaymentModal()
    {

        Log::info('Close Create Payment method called.');
        $this->createPaymentModal = false;
    }

    public function CreatePayment()
    {
        Log::info('Create Payment method called.');

        // Validate the input data
        $this->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_type' => 'required|in:Room Rent,House Rent,Activity Fee,Event Hall,Event Package,Security Deposit,Remaining Balance',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Ensure the invoice exists
        if (!$this->invoice) {
            abort(404, 'No invoice found for this transaction.');
        }

        // Create the payment record
        Payment::create([
            'invoice_id' => $this->invoice->id,
            'amount_paid' => $this->amount_paid,
            'mode_of_payment' => 'cash',
            'payment_type' => $this->payment_type,
            'payment_date' => $this->payment_date,
            'payment_status' => 'completed',
            'notes' => $this->notes,
            'currency' => 'PHP',
            'verified_at' => now(),
        ]);



        // Update the invoice with the new amount paid and balance due
        $newAmountPaid = $this->invoice->amount_paid + $this->amount_paid;
        $newBalanceDue = max($this->invoice->sub_total - $newAmountPaid, 0);

        $this->invoice->update([
            'amount_paid' => $newAmountPaid,
            'balance_due' => $newBalanceDue,
        ]);

        // If the balance is 0, update invoice status to 'completed'
        if ($newBalanceDue == 0) {
            $this->invoice->update([
                'invoice_status' => 'completed',
                'completed_at' => now(),
            ]);

            // Log status change
            Log::info("Invoice status updated to 'completed' because balance due is 0.");
        }

        // If the new amount paid is greater than or equal to the deposit amount, update transaction status to 'receipt_verified'
        if ($newAmountPaid >= $this->transaction->deposit_amount) {
            $this->transaction->update(['transaction_status' => 'receipt_verified']);
            Log::info("Transaction status updated to 'reserved' because amount paid is greater than or equal to deposit amount.");
        }



        // Reset the form fields after successful creation
        $this->reset([
            'amount_paid',
            'mode_of_payment',
            'payment_type',
            'payment_date',
            'payment_status',
            'notes',
            'currency',
            'verified_at',
        ]);

        // Redirect to the same reservation view to refresh data
        return redirect()->route('admin.view-lease', ['transaction' => $this->transaction->id])
            ->with('success', 'Payment created successfully.');
    }
}
