<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use App\Models\Transaction;
use Livewire\Component;

class DeletedEvents extends Component
{
    public $deletedEvents;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedEvents();
    }

    public function fetchDeletedEvents()
    {
        //$this->deletedEvents = Transaction::onlyTrashed()->orderBy('created_at', 'ASC')->get();
        $this->deletedEvents = Transaction::onlyTrashed()
        ->where('reservation_type_id', 3) //Filter events  
        ->with(['properties', 'transactionUser'])
        ->latest()
        ->get();
    }

    public function restoreEvent($eventId)
    {
        $event = Transaction::withTrashed()->find($eventId);
        if ($event) {
            $event->restore();
            session()->flash('message', 'Event restored successfully.');
            $this->fetchDeletedEvents();
        }
    }

    public function deleteEventForever($eventId)
    {
        $event = Transaction::withTrashed()->find($this->confirmItemDelete);
        if ($event) {
            $event->forceDelete();
            session()->flash('message', 'Event permanently deleted.');
            $this->fetchDeletedEvents();
        }
        $this->confirmItemDelete = false;
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_events', []);

        $deletedIds = $this->deletedEvents->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedEvents as $index => $event) {
                $fakeIDs[$event->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_event' => $fakeIDs]);
        }

        return view('livewire.admin.events.deleted-events', [
            'deletedEvents' => $this->deletedEvents,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
