<?php

namespace App\Livewire\Admin\RoomCategories;

use App\Models\RoomCategory;
use Livewire\Component;

class DeletedRoomCategories extends Component
{
    public $deletedRoomCategories;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedRoomCategories();
    }

    public function fetchDeletedRoomCategories()
    {
        $this->deletedRoomCategories = RoomCategory::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreRoomCategory($roomCategoryId)
    {
        $roomCategory = RoomCategory::withTrashed()->find($roomCategoryId);
        if ($roomCategory) {
            $roomCategory->restore();
            session()->flash('message', 'Room category restored successfully.');
            $this->fetchDeletedRoomCategories();
        }
    }

    public function deleteRoomCategoryForever($roomCategoryId)
    {
        $roomCategory = RoomCategory::withTrashed()->find($this->confirmItemDelete);
        if ($roomCategory) {
            $roomCategory->forceDelete();
            session()->flash('message', 'Room category permanently deleted.');
            $this->fetchDeletedRoomCategories();
        }
        $this->confirmItemDelete = false;
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_roomCategory', []);

        $deletedIds = $this->deletedRoomCategories->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedRoomCategories as $index => $category) {
                $fakeIDs[$category->id] = 'RCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_roomCategory' => $fakeIDs]);
        }

        return view('livewire.admin.room-categories.deleted-room-categories', [
            'deletedRoomCategories' => $this->deletedRoomCategories,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
