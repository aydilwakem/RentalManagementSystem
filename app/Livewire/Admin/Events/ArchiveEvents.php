<?php

namespace App\Livewire\Admin\Events;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ArchiveEvents extends Component
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
        $events = Transaction::query()
            ->select('trn_transactions.*')
            ->distinct()
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id')
            ->with(['transactionUser', 'properties' => function ($query) {
                $query->where('property_type_id', 3); // Event halls
            }])
            ->where('reservation_type_id', 3) // Event reservations
            ->archived() // Use the archived scope
            ->when($this->search !== '', function ($query) {
                $query
                    ->whereHas('transactionUser', function ($subQuery) {
                        $subQuery
                            ->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhere('company_name', 'like', '%' . $this->search . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%']);
                    })
                    ->orWhereHas('properties', function ($subQuery) {
                        $subQuery->where('name_number', 'like', '%' . $this->search . '%')
                            ->where('property_type_id', 3);
                    })
                    ->orWhere('transaction_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.events.archive-events', compact('events'));
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
        $this->restoreTitle = 'Restore Event';
        $this->restoreMessage = "Are you sure you want to restore event {$transactionNumber}?";
        $this->confirmingRestore = true;
    }

    public function restoreEvent()
    {
        $transaction = Transaction::find($this->restoreId);
        
        if ($transaction) {
            // Restore to 'done' status since it was completed before archiving
            $transaction->update(['transaction_status' => 'done']);
            
            session()->flash('message', 'Event successfully restored!');
        }
        
        $this->confirmingRestore = false;
        $this->resetPage();
    }

    public function showDeleteModal($id, $transactionNumber)
    {
        $this->deleteId = $id;
        $this->deleteTitle = 'Delete Event';
        $this->deleteMessage = "Are you sure you want to permanently delete event {$transactionNumber}? This action cannot be undone.";
        $this->actionButtonType = 'danger';
        $this->confirmingDelete = true;
    }

    public function deleteEvent($id = null)
    {
        $eventId = $id ?? $this->deleteId;
        $event = Transaction::find($eventId);
        
        if ($event) {
            $event->delete(); // Permanently delete the event
            session()->flash('message', 'Event successfully deleted!');
            
            Log::info("Event {$event->transaction_number} permanently deleted from archives.");
        } else {
            session()->flash('error', 'Event not found.');
        }
        
        $this->confirmingDelete = false;
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }
}