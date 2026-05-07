<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\Event;
use App\Models\EventHall;
use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventHall extends Component
{
    // Create a public property 
    public Property $eventHall;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
    // Function for deleting a record
    public function deleteEventHall()
    {
        if ($this->confirmItemDelete) {
        $eventHall = Property::find($this->confirmItemDelete);

        if (!$eventHall) {
            session()->flash('error', 'Event Hall not found!');
            return redirect()->route('admin.event-halls');
        }

        // Check if the event hall is linked to any transaction
        $usedInTransactions = Transaction::whereHas('properties', function ($query) use ($eventHall) {
            $query->where('property_id', $eventHall->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete because hall is active in Transactions
            $this->confirmItemDelete = null;
            return;
        }

        // Detach all features/amenities
        $eventHall->features()->detach();

        // Delete the hall
        $eventHall->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Hall successfully deleted!');
        }

        return redirect()->route('admin.event-halls');
    }

    public function render()
    {
        return view('livewire.admin.event-halls.view-event-hall');
    }
}
