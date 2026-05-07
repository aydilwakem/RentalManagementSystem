<?php

namespace App\Livewire\Admin\Reservations;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class ArchiveReservations extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $confirmingRestore = false;
    public $restoreId;
    public $restoreTitle = '';
    public $restoreMessage = '';


    public $confirmingDelete = false;
    public $deleteId;
    public $deleteTitle = '';
    public $deleteMessage = '';
    public $actionButtonType = 'default';


    public function render()
    {
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->distinct()
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id')
            ->with(['transactionUser', 'properties' => function ($query) {
                $query->where('property_type_id', 1);
            }])
            ->where('reservation_type_id', 2)
            ->archived() // Use the archived scope
            ->when($this->search !== '', function ($query) {
                $query
                    ->whereHas('transactionUser', function ($subQuery) {
                        $subQuery
                            ->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
                    })
                    ->orWhereHas('properties', function ($subQuery) {
                        $subQuery->where('name_number', 'like', '%' . $this->search . '%')
                            ->where('property_type_id', 1);
                    });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.reservations.archive-reservations', compact('transactions'));
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    public function showRestoreModal($id, $transactionNumber)
    {
        $this->restoreId = $id;
        $this->restoreTitle = 'Restore Reservation';
        $this->restoreMessage = "Are you sure you want to restore reservation {$transactionNumber}?";
        $this->confirmingRestore = true;
    }

    public function restoreReservation()
    {
        $transaction = Transaction::find($this->restoreId);
        
        if ($transaction) {
            // Restore to 'done' status since it was completed before archiving
            $transaction->update(['transaction_status' => 'done']);
            
            session()->flash('message', 'Reservation successfully restored!');
        }
        
        $this->confirmingRestore = false;
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }

        public function showDeleteModal($id, $transactionNumber)
    {
        $this->deleteId = $id;
        $this->deleteTitle = 'Delete Reservation';
        $this->deleteMessage = "Are you sure you want to permanently delete reservation {$transactionNumber}? This action cannot be undone.";
        $this->actionButtonType = 'danger';
        $this->confirmingDelete = true;
    }

    /**
     * Deletes the selected transaction from the database
     */
    public function deleteReservation($id = null)
    {
        // Use the provided ID or the one from the modal
        $transactionId = $id ?? $this->deleteId;
        $transaction = Transaction::find($transactionId);
        
        if ($transaction) {
            $transaction->delete(); // Permanently delete the transaction
            session()->flash('message', 'Reservation successfully deleted!');
            
            Log::info("Reservation {$transaction->transaction_number} permanently deleted from archives.");
        } else {
            session()->flash('error', 'Transaction not found.');
        }
        
        $this->confirmingDelete = false;
        $this->resetPage();
    }

}