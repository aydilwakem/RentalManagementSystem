<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class EditReservation extends Component
{
    // Relationship: Transaction->Invoice->Payments
    public $transaction; // Holds the current transaction
    public $invoice; // Holds the invoice associated with the transaction
    public $activities; // Holds all activities associated with the transaction

    public $guestDetails;
    public $properties; // Holds all properties associated with the transaction

    public $payments; // Holds all payments associated with the invoice
    public $totalAddons; // Holds the total amount of addons
    public $totalRooms; // Holds the total amount of rooms





    public function render()
    {
        return view('livewire.admin.reservations.edit-reservation');
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
            'transactionUser',      // Load the user related to the transaction
            'guestDetails',         // Load additional guest details associated with the transaction
            'properties',           // Load the properties (e.g., rooms) included in the transaction
            'activities' => function ($query) {
                $query->withPivot('quantity', 'amount', 'activity_datetime', 'status');  // Ensure pivot data is loaded
            },
        ]);

        // If there's no invoice associated with the transaction, abort and return a 404 error.
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        // Assign the loaded models to the component's public properties for use in the Blade view
        $this->transaction = $transaction;                 // Store the full transaction
        $this->invoice = $transaction->invoice;            // Store the invoice details
        $this->guestDetails = $transaction->guestDetails;  // Store guest details for display
        $this->activities = $transaction->activities()->withPivot('quantity', 'amount', 'activity_datetime', 'status')->get();
        $this->properties = $transaction->properties;      // Store properties (rooms)

        // Store payment records from the invoice, or an empty collection if none
        $this->payments = $this->invoice->payments ?? collect();

        // Get computed totals from model accessors (defined in the Transaction model)
        $this->totalRooms = $transaction->total_rooms;     // Total cost from rooms (via accessor)
        $this->totalAddons = $transaction->total_addons;   // Total cost from addons (via accessor)
    }
}
