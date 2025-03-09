<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use App\Models\RoomCategory;
use Livewire\WithPagination;

class ViewRoomCategories extends Component
{

    use WithPagination;

    public $sortBy = 'id';
    public $sortDir = 'ASC';

    public $search = '';
    public $perPage = 5;

    public function deleteCategory($id)
    {
        // Find the room category by ID
        $roomCategory = RoomCategory::find($id);

        if ($roomCategory) {
            // Detach related amenities before deleting
            $roomCategory->amenities()->detach();

            // Delete the room category
            $roomCategory->delete();

            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $roomCategories = RoomCategory::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
        return view('livewire.admin.room-categories.view-room-categories', compact('roomCategories'));
    }
}
