<?php

namespace App\Livewire\Admin\Transactions\OldTransaction;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewTransactions extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $sortBy = 'check_in_date';
    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $perPage = 5;
    public $statusFilter = ''; // Holds the selected room status

    public $confirmItemDelete = false;

    public function mount()
    {
        if (!session()->has('fake_ids_transactions')) {
            session(['fake_ids_transactions' => []]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            $this->confirmItemDelete = false;

            // Refresh fake IDs
            $transactions = Transaction::orderBy('check_in_date', 'ASC')->get();
            $fakeIDs = [];
            foreach ($transactions as $index => $transaction) {
                $fakeIDs[$transaction->id] = 'TXN-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_transactions' => $fakeIDs]);

            session()->flash('message', 'Transaction successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->where('isPaid', true) // Filter only paid transaction
            ->where('first_name', 'like', '%' . $this->search . '%')
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $fakeIDs = session('fake_ids_transactions', []);

        if (count($fakeIDs) !== Transaction::count()) {
            $fakeIDs = [];
            foreach (Transaction::orderBy('check_in_date', 'ASC')->get() as $index => $transaction) {
                $fakeIDs[$transaction->id] = 'TXN-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_transactions' => $fakeIDs]);
        }

        return view('livewire.admin.transactions.old-transaction.view-transactions', [
            'transactions' => $transactions,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
