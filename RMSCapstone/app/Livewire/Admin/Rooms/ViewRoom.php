<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Room;

#[Layout('layouts.app')]
class ViewRoom extends Component
{
    // Create a public property 
    public Room $room;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function to find the model of the record
    public function mount(Room $room)
    {
        // Load the room with its related category
        $this->room = $room->load('category');
    }

    // Function for deleting a record
    public function deleteRoom(Room $room)
    {
        if (!$room) {
            session()->flash('error', 'Room not found!');
            return;
        }

        if ($this->confirmItemDelete) {
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
