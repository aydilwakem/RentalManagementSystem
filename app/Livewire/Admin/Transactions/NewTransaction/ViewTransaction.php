<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Transaction;

#[Layout('layouts.app')]
class ViewTransaction extends Component
{
    public Transaction $transaction; // Holds the transaction details
    public $selectedTransaction; // Stores the selected transaction for confirmation
    public $confirmItemDelete = false; // Stores the ID of the transaction to be deleted

    public $confirmItemReceipt = false; // Flag to track receipt confirmation modal


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

    /**
     * Triggers receipt confirmation modal and loads transaction details
     */
    public function confirmReceipt($id)
    {
        $this->selectedTransaction = Transaction::find($id); // Fetch transaction data
        $this->confirmItemReceipt = true; // Show receipt confirmation modal
    }

    /**
     * Marks the transaction as paid and updates the database
     */
    public function confirmPaymentReceipt()
    {
        $transaction = Transaction::find($this->selectedTransaction->id);

        if ($transaction) {
            $transaction->update(['isPaid' => true]); // Updates only the 'isPaid' field
            session()->flash('message', 'Payment Receipt confirmed successfully.'); // Success message
        } else {
            session()->flash('error', 'Payment Receipt confirmation failed.'); // Error message if transaction not found
        }

        $this->confirmItemReceipt = false; // Close confirmation modal
    }

    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.view-transaction', [
            'transactions' => $this->transaction, // Pass transaction data to the view
        ]);
    }
}
