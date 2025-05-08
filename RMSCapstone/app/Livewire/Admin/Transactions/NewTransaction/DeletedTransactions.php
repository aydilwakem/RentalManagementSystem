<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use App\Models\Transaction;
use Livewire\Component;

class DeletedTransactions extends Component
{
    public $deletedNewTransactions;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedNewTransactions();
    }

    public function fetchDeletedNewTransactions()
    {
        $this->deletedNewTransactions = Transaction::onlyTrashed()
            ->where('reservation_type_id', 2)
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    public function restoreTransaction($transactionId)
    {
        $transaction = Transaction::withTrashed()->find($transactionId);
        if ($transaction) {
            $transaction->restore(); // Restore the transaction
            session()->flash('message', 'Transaction restored successfully.');
            $this->deletedNewTransactions = Transaction::onlyTrashed()->get();
        }
    }

    public function deleteTransactionForever($transactionId)
    {
        $transaction = Transaction::withTrashed()->find($this->confirmItemDelete);
        if ($transaction) {
            $transaction->forceDelete(); // Permanently delete the room
            session()->flash('message', 'Transaction permanently deleted.');
            $this->fetchDeletedNewTransactions();
        }
        $this->confirmItemDelete = false;
    }


    public function render()
    {
        // Generate fake IDs for deleted transactions
        $fakeIDs = session('fake_ids_transactions', []);

        $deletedIds = $this->deletedNewTransactions->pluck('id')->toArray();

        // Refresh fake IDs if mismatch or count changes
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedNewTransactions as $index => $transaction) {
                $fakeIDs[$transaction->id] = 'TXN-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_transactions' => $fakeIDs]);
        }

        return view('livewire.admin.transactions.new-transaction.deleted-transactions', [
            'deletedNewTransactions' => $this->deletedNewTransactions,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
