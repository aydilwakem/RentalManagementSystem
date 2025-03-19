<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RoomCategory;

#[Layout('layouts.app')]
class ViewRoomCategory extends Component
{
    // Create a public property 
    public RoomCategory $roomCategory;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }


    // Function to find the model of the record
    public function mount(RoomCategory $roomCategory)
    {
        // Load amenities with the room category 
        $this->roomCategory = $roomCategory->load('amenities');
    }

    // Function for deleting a record
    public function deleteCategory(RoomCategory $roomCategory)
    {
        if (!$roomCategory) {
            session()->flash('error', 'Room Category not found!');
            return;
        }

        if ($roomCategory) {
            // Detach related amenities before deleting
            $roomCategory->amenities()->detach();

            // Delete the room category
            if ($this->confirmItemDelete) {
                $roomCategory->delete();
                $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');

            // Redirect to the admin room categories page
            return redirect()->route('admin.room-categories');
        }
    }
    }

    public function render()
    {
        return view('livewire.admin.room-categories.view-room-category');
    }
}
