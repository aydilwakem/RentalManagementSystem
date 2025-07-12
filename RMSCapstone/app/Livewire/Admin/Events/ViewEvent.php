<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventHall;
use App\Models\EventType;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

#[Layout('layouts.app')]
class ViewEvent extends Component
{
    // Create a public property
    public Transaction $event;
    public $transaction;
    public $invoice;
    public $payments;

    public $halls;
    public $guests;
    public $event_invoice;
    public $eventTypes;

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

    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    //To display foreign keys
    public function mount(Transaction $event)
    {
        $this->eventTypes = EventType::all();
        $this->event_invoice = Invoice::where('invoice_type', 'Event_Hall')->get();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $this->guests = TransactionUser::where('trn_user_type', 'guest')->get();
        $this->loadTransactionData($event);

        //default date in create payment
        $now = now('Asia/Manila');
        $this->payment_date = $now->format('Y-m-d');
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


    public function exportEventDetails()
    {
        //eager load the relationship
         $event = Transaction::with([
            'invoice.payments',
        ])->findOrFail($this->transaction->id);

        $pdf = Pdf::loadView('livewire.admin.events.event-details', [
            'event' => $event,  // Pass the actual event
            //pass the relationship
            'invoice' => $event->invoice,
            'payments' => $event->invoice->payments,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'event-details-' . $event->start_datetime . '.pdf');
    }

    public function deleteEventItem(Transaction $event)
    {
        if (!$event) {
            session()->flash('error', 'Event not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            if (in_array($event->transaction_status, ['done', 'terminated'])) {
                $event->delete();
                $this->confirmItemDelete = false;

                session()->flash('message', 'Event successfully deleted!');
                return redirect()->route('admin.events');
            } else {
                // Set modal flag if event is not deletable
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = false;
            }
        }
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
        return redirect()->route('admin.view-event', ['event' => $this->event->id])
            ->with('success', 'Payment created successfully.');
    }

    public function render()
    {
        return view('livewire.admin.events.view-event');
    }
}
