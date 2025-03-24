<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Transaction;
use App\Models\TransactionResident;
use App\Models\Room;
use App\Models\PaymentMethod;
use App\Models\Activity;

#[Layout('layouts.app')]
class ViewTransaction extends Component
{
    public $transaction;
    public $resident;
    public $confirmItemDelete = false;

    public function mount($transactionId)
    {
        $this->transaction = Transaction::find($transactionId);

        if (!$this->transaction) {
            abort(404, 'Transaction not found');
        }

        $this->resident = $this->transaction->residents;
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    /**
     * Deletes the selected transaction from the database
     */
    public function deleteTransaction()
    {
        $transaction = Transaction::find($this->confirmItemDelete);
        if ($transaction) {
            $transaction->delete(); // Permanently deletes the transaction
            $this->confirmItemDelete = false;
            session()->flash('message', 'Transaction successfully deleted!'); // Success message
        }
    }

    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.view-transaction', [
            'transactions' => $this->transaction,
            'residents' => $this->resident,
        ]);
    }
}
