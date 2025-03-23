<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewTransactions extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'ASC';

    public $statusFilter = '';
    public $selectedTransaction;
    public $confirmItemDelete = false;
    public $confirmItemReceipt = false;

    public function mount()
    {
        if (!session()->has('fake_ids_transactions')) {
            session(['fake_ids_transactions' => []]);
        }
    }

    /**
     * Delete transaction
     */

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteTransaction()
    {
        $transaction = Transaction::find($this->confirmItemDelete);
        if ($transaction) {
            $transaction->delete();
            $this->confirmItemDelete = false;

            session()->flash('message', 'Transaction successfully deleted!');
        }
    }

    public function confirmReceipt($id)
    {
        $this->selectedTransaction = Transaction::find($id); // load first the data before opening the modal
        $this->confirmItemReceipt = true;
    }



    public function confirmPaymentReceipt()
    {
        $transaction = Transaction::find($this->selectedTransaction->id);

        if ($transaction) {
            $transaction->update(['isPaid' => true]); // Only update 'isPaid'
            session()->flash('message', 'Payment Receipt confirmed successfully.');
        } else {
            session()->flash('error', 'Payment Receipt confirmation failed.');
        }

        // Close the modal
        $this->confirmItemReceipt = false;
    }

    /**
     * Sorts records
     */
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    /**
     * Updates the isPaid condition to true.
     */

    public function confirmReservation($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            $transaction->update(['isReserved' => true]);
            session()->flash('message', 'Reservation confirmed successfully.');
        } else {
            session()->flash('error', 'Reservation not found.');
        }
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->where('isConfirmed', false)
            ->where('isReserved', false)
            ->where('first_name', 'like', '%' . $this->search . '%')
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.transactions.new-transaction.view-transactions', [
            'transactions' => $transactions,
        ]);
    }
}
