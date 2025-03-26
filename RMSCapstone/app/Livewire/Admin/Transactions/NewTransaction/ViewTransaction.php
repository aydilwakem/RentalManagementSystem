<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Transaction;

#[Layout('layouts.app')]
class ViewTransaction extends Component
{
    public Transaction $transaction; // Holds the transaction details
    public $confirmItemDelete = false; // Stores the ID of the transaction to be deleted

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteTransaction(Transaction $transaction)
    {
        if (!$transaction) {
            session()->flash('error', 'Transaction not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            $transaction->delete();
            $this->confirmItemDelete = false;

            session()->flash('message', 'Transaction successfully deleted!');
            return redirect()->route('admin.view-new-transactions');
        }
    }

    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.view-transaction', [
            'transactions' => $this->transaction, // Pass transaction data to the view
        ]);
    }
}
