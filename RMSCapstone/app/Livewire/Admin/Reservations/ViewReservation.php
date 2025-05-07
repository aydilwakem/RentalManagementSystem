<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ViewReservation extends Component
{

    // Relationship: Transaction->Invoice->Payments
    public $transaction; // Holds the current transaction
    public $invoice; // Holds the invoice associated with the transaction
    public $guestDetails; // Holds all guest associated with the transaction
    public $payments; // Holds all payments associated with the invoice
    public $totalAddons; // Holds the total amount of addons
    public $totalRooms; // Holds the total amount of rooms

    public function mount(Transaction $transaction)
    {
        // Eager-load relationships only if not already loaded
        $transaction->loadMissing([
            'invoice.payments',
            'transactionUser',
            'guestDetails',
            'properties',
            'activities',
        ]);

        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        $this->transaction = $transaction;
        $this->invoice = $transaction->invoice;
        $this->guestDetails = $transaction->guestDetails;
        $this->payments = $this->invoice->payments ?? collect();

        // Use model accessors for calculated totals
        $this->totalRooms = $transaction->total_rooms; // In the Transaction Modal 
        $this->totalAddons = $transaction->total_addons;
    }


    public function render()
    {
        return view('livewire.admin.reservations.view-reservation');
    }
}
