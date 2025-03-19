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
            $this->fetchdeletedRooms();
        }
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
