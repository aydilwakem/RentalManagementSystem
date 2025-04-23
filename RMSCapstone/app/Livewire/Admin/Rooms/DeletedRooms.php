<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Property;

class DeletedRooms extends Component
{
    public $deletedRooms;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedRooms();
    }

    public function fetchDeletedRooms()
    {
        $this->deletedRooms = Property::onlyTrashed()->ofType('Room')->orderBy('created_at', 'ASC')->get();
    }

    public function restoreRoom($roomId)
    {
        $room = Property::withTrashed()->ofType('Room')->find($roomId);
        if ($room) {
            $room->restore(); // Restore the room
            session()->flash('message', 'Room restored successfully.');
            $this->fetchDeletedRooms();
        }
    }

    public function deleteRoomForever($roomId)
    {
        $room = Property::withTrashed()->find($this->confirmItemDelete);
        if ($room) {
            $room->forceDelete(); // Permanently delete the room
            session()->flash('message', 'Room permanently deleted.');
            $this->fetchDeletedRooms();
        }
        $this->confirmItemDelete = false;
    }


    public function render()
    {
        // Generate fake IDs for deleted room rates
        $fakeIDs = session('fake_ids_rooms', []);

        $deletedIds = $this->deletedRooms->pluck('id')->toArray();

        // Refresh fake IDs if mismatch or count changes
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedRooms as $index => $room) {
                $fakeIDs[$room->id] = 'RM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_rooms' => $fakeIDs]);
        }

        return view('livewire.admin.rooms.deleted-rooms', [
            'deletedRooms' => $this->deletedRooms,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
