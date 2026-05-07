<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ArchiveLeases extends Component
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
        $transactions = Transaction::with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 1) // House reservation type
            ->archived() // Use the archived scope
            ->when($this->search !== '', function ($query) {
                $search = '%' . $this->search . '%';
                $query->whereHas('transactionUser', function ($subQuery) use ($search) {
                    $subQuery
                        ->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search]);
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.properties.leases.archive-leases', compact('transactions'));
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
        $this->restoreTitle = 'Restore Lease';
        $this->restoreMessage = "Are you sure you want to restore lease {$transactionNumber}?";
        $this->confirmingRestore = true;
    }

    public function restoreLease()
    {
        $transaction = Transaction::find($this->restoreId);
        
        if ($transaction) {
            // Restore to 'done' status since it was completed before archiving
            $transaction->update(['transaction_status' => 'done']);
            
            session()->flash('message', 'Lease successfully restored!');
        }
        
        $this->confirmingRestore = false;
        $this->resetPage();
    }

    public function showDeleteModal($id, $transactionNumber)
    {
        $this->deleteId = $id;
        $this->deleteTitle = 'Delete Lease';
        $this->deleteMessage = "Are you sure you want to permanently delete lease {$transactionNumber}? This action cannot be undone.";
        $this->actionButtonType = 'danger';
        $this->confirmingDelete = true;
    }

    public function deleteLease($id = null)
    {
        $transactionId = $id ?? $this->deleteId;
        $transaction = Transaction::find($transactionId);
        
        if ($transaction) {
            $transaction->delete(); // Permanently delete the transaction
            session()->flash('message', 'Lease successfully deleted!');
            
            Log::info("Lease {$transaction->transaction_number} permanently deleted from archives.");
        } else {
            session()->flash('error', 'Transaction not found.');
        }
        
        $this->confirmingDelete = false;
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }
}