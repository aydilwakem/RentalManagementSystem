<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;

#[Layout('layouts.app')]
class ViewRoom extends Component
{
    // Create a public property 
    public Property $room;

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
    public function deleteRoom(Property $room)
    {
        if (!$room) {
            session()->flash('error', 'Room not found!');
            return;
        }

        if ($this->confirmItemDelete) {

            // Detach the amenities (features) associated with the room
            $room->features()->detach();

            $room->delete();

            $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'Room successfully deleted!');

            // Redirect to the admin rooms page
            return redirect()->route('admin.rooms');
        }
    }



    public function render()
    {
        return view('livewire.admin.rooms.view-room');
    }
}
