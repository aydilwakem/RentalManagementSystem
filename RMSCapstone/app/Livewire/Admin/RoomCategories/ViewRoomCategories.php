<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use App\Models\RoomCategory;

class ViewRoomCategories extends Component
{
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

    public function viewCategory($categoryId)
    {
        return redirect()->route('admin.view-room-category', ['id' => $categoryId]);
    }

    public function render()
    {
        $roomCategories = RoomCategory::all(); // Fetch all room categories

        return view('livewire.admin.room-categories.view-room-categories', compact('roomCategories'));
    }
}
