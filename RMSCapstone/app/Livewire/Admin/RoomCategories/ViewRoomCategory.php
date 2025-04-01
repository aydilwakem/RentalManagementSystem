<?php

namespace App\Livewire\Admin\RoomCategories;

use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RoomCategory;
use Illuminate\Database\QueryException;

#[Layout('layouts.app')]
class ViewRoomCategory extends Component
{
    // Create a public property 
    public RoomCategory $roomCategory;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

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
    public function deleteCategory()
    {
        //find id
        $roomCategory = RoomCategory::find($this->confirmItemDelete);

        if (!$roomCategory) {
            session()->flash('error', 'Room Category not found!');
            return;
        }

        if ($roomCategory) {
            // Store the detached amenities IDs in session before deleting
        session()->put('detached_amenities', $roomCategory->amenities->pluck('id')->toArray());
            // Detach related amenities before deleting
            $roomCategory->amenities()->detach();

            // Check if the category is referenced in another table
            if (Room::where('room_category_id', $roomCategory->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
            }

            try{
            $roomCategory->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');

            // Redirect to the admin room categories page
            return redirect()->route('admin.room-categories');
        }catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) { 
                $this->cannotDeleteItem = true; // Show the cannot delete modal
            } else {
                throw $e; // Re-throw other exceptions
            }
        }
    }
    }
    

    public function render()
    {
        return view('livewire.admin.room-categories.view-room-category');
    }
}
