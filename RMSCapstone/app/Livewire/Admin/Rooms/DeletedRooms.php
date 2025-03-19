<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Room;

class DeletedRooms extends Component
{
    public $deletedRooms;

    public function mount()
    {
        $this->deletedRooms = Room::onlyTrashed()->get(); // Fetch only soft deleted rooms
    }

    public function restoreRoom($roomId)
    {
        $room = Room::withTrashed()->find($roomId);
        if ($room) {
            $room->restore(); // Restore the room
            session()->flash('message', 'Room restored successfully.');
            $this->deletedRooms = Room::onlyTrashed()->get();
        }
    }

    public function deleteForever($roomId)
    {
        $room = Room::withTrashed()->find($roomId);
        if ($room) {
            $room->forceDelete(); // Permanently delete the room
            session()->flash('message', 'Room permanently deleted.');
            $this->deletedRooms = Room::onlyTrashed()->get();
        }
    }


    public function render()
    {
        return view('livewire.admin.rooms.deleted-rooms', [
            'deletedRooms' => $this->deletedRooms,
        ]);
    }
}
