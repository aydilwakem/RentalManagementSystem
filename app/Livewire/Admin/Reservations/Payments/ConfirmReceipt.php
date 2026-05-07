<?php

namespace App\Livewire\Admin\Reservations\Payments;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Invoice;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ConfirmReceipt extends Component
{

    // Relationship: Transaction->Invoice->Payments
    public Transaction $transaction; // Holds the current transaction
    public Invoice $invoice; // Holds the invoice associated with the transaction
    public $payments; // Holds all payments associated with the invoice



    public function mount(Transaction $transaction)
    {
        // Eager-load all related models in one go
        $transaction->load([
            'invoice.payments',
            'transactionUser',
            'properties',      // many-to-many through transaction_properties
            'activities',      // many-to-many through transaction_activities
        ]);

        // Check if invoice exists
        if (!$transaction->invoice) {
            abort(404, 'Invoice not found for this transaction.');
        }

        $this->transaction = $transaction;
        $this->invoice = $transaction->invoice;

        // Payments are already eager-loaded, so no new query is made here
        $this->payments = $this->invoice->payments;
    }


    public function render()
    {
        return view('livewire.admin.reservations.payments.confirm-receipt');
    }
}
