<?php

namespace App\Livewire\Admin\RoomCategories;

use App\Models\RoomCategory;
use Illuminate\Database\QueryException;
use Livewire\Component;

class DeletedRoomCategories extends Component
{
    public $deletedRoomCategories;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Will appear if parent table item is still in soft delete

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
            
            // Check if there are any detached amenities stored in the session
        $detachedAmenities = session()->get('detached_amenities', []);

        // If detached amenities exist, re-attach them
        if (!empty($detachedAmenities)) {
            $roomCategory->amenities()->attach($detachedAmenities);
            // Clear the session after reattaching
            session()->forget('detached_amenities');
        }
            session()->flash('message', 'Room category restored successfully.');
            $this->fetchDeletedRoomCategories();
        }
    }

    public function deleteRoomCategoryForever($roomCategoryId)
    {
        try{
        $roomCategory = RoomCategory::withTrashed()->find($this->confirmItemDelete);
        if ($roomCategory) {
            $roomCategory->forceDelete();
            session()->flash('message', 'Room category permanently deleted.');
            $this->fetchDeletedRoomCategories();
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
