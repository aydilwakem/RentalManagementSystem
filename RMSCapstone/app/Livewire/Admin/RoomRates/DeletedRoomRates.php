<?php

namespace App\Livewire\Admin\RoomRates;

use App\Models\RoomRate;
use Livewire\Component;

class DeletedRoomRates extends Component
{
    public $deletedRoomRates;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedRoomRates();
    }

    public function fetchDeletedRoomRates()
    {
        // Fetch soft-deleted room rates by creation time
        $this->deletedRoomRates = RoomRate::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreRoomRate($roomRateId)
    {
        $roomRate = RoomRate::withTrashed()->find($roomRateId);
        if ($roomRate) {
            $roomRate->restore();
            session()->flash('message', 'Room rate restored successfully.');
            $this->fetchDeletedRoomRates();
        }
        
    }

    public function deleteRoomRateForever($roomRateId)
    {
        $roomRate = RoomRate::withTrashed()->find($this->confirmItemDelete);
        if ($roomRate) {
            $roomRate->forceDelete();
            session()->flash('message', 'Room rate permanently deleted.');
            $this->fetchDeletedRoomRates();
        }
        $this->confirmItemDelete = false;
    }


    public function render()
    {

        // Generate fake IDs for deleted room rates
        $fakeIDs = session('fake_ids_roomRate', []);

        $deletedIds = $this->deletedRoomRates->pluck('id')->toArray();

        // Refresh fake IDs if mismatch or count changes
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedRoomRates as $index => $rate) {
                $fakeIDs[$rate->id] = 'RR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_roomRate' => $fakeIDs]);
        }

        return view('livewire.admin.room-rates.deleted-room-rates', [
            'deletedRoomRates' => $this->deletedRoomRates,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
