<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;
use App\Models\Transaction;

#[Layout('layouts.app')]
class ViewRoom extends Component
{
    // Create a public property 
    public Property $room;

    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function to find the model of the record
    public function mount(Property $room)
    {
        // Load room with category and features
        $this->room = $room->load('category', 'features');
    }

    // Function for deleting a record
    public function deleteRoom()
    {
        if ($this->confirmItemDelete) {
        $room = Property::find($this->confirmItemDelete);

        if (!$room) {
            session()->flash('error', 'Event Hall not found!');
            return redirect()->route('admin.event-halls');
        }

        // Check if the event hall is linked to any transaction
        $usedInTransactions = Transaction::whereHas('properties', function ($query) use ($room) {
            $query->where('property_id', $room->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete because hall is active in Transactions
            $this->confirmItemDelete = null;
            return;
        }

        // Detach all features/amenities
        $room->features()->detach();

        // Delete the hall
        $room->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Room successfully deleted!');
        }

        return redirect()->route('admin.rooms');
    }


    public function render()
    {
        return view('livewire.admin.rooms.view-room');
    }
}
