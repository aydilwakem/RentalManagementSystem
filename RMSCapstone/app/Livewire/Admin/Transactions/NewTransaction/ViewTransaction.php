<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Transaction;
use App\Models\Room;
use App\Models\PaymentMethod;
use App\Models\Activity;

#[Layout('layouts.app')]
class ViewTransaction extends Component
{
    public ?Transaction $transaction = null; // Ensure it's nullable

    public function mount($transactionId)
    {
        // Fetch the transaction based on the provided ID
        $this->transaction = Transaction::find($transactionId);

        // Handle case if transaction is not found
        if (!$this->transaction) {
            abort(404, 'Transaction not found');
        }
    }

    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.view-transaction', [
            'transactions' => $this->transaction, // Use singular since it's one transaction
        ]);
    }
}
