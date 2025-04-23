<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use App\Models\Property;
use Illuminate\Database\QueryException;
use Livewire\Component;

class DeletedEventHalls extends Component
{
    public $deletedEventHalls;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Will appear if parent table item is still in soft delete

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedEventHalls();
    }

    public function fetchDeletedEventHalls()
    {
        $this->deletedEventHalls = Property::onlyTrashed()->ofType('Event Hall')->orderBy('created_at', 'ASC')->get();    
    }

    public function restoreEventHall($eventHallId)
    {
        $eventHall = Property::withTrashed()->ofType('Event Hall')->find($eventHallId);        
        if ($eventHall) {
            $eventHall->restore();
            session()->flash('message', 'Event hall restored successfully.');
            $this->fetchDeletedEventHalls();
        }
    }

    public function deleteEventHallForever($eventHallId)
    {
        try{
            $eventHall = Property::withTrashed()->find($this->confirmItemDelete);
            if ($eventHall) {
                $eventHall->forceDelete(); // Permanently delete the room
                session()->flash('message', 'Event Hall permanently deleted.');
                $this->fetchDeletedEventHalls();
            }
        $this->confirmItemDelete = false;
    }catch (QueryException $e) {
        // Check if the error is an integrity constraint violation
        if ($e->getCode() == 23000) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = false;
        } else {
            throw $e; // Re-throw other exceptions
        }
    }
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_events', []);

        $deletedIds = $this->deletedEventHalls->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedEventHalls as $index => $eventHall) {
                $fakeIDs[$eventHall->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_event' => $fakeIDs]);
        }

        return view('livewire.admin.event-halls.deleted-event-halls',[
            'deletedEventHalls' => $this->deletedEventHalls,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
