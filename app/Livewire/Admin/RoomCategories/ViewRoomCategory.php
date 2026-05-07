<?php

namespace App\Livewire\Admin\RoomCategories;

use App\Models\Property;
use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\PropertyCategory;
use Illuminate\Database\QueryException;

#[Layout('layouts.app')]
class ViewRoomCategory extends Component
{
    // Create a public property 
    public PropertyCategory $roomCategory;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function for deleting a record
    // Function for deleting a record
    public function deleteRoomCategory()
    {
            $roomCategory = PropertyCategory::find($this->confirmItemDelete);

            if (!$roomCategory) {
                session()->flash('error', 'Event Category not found.');
                return;
            }

            // Check if the category is referenced in another table
        if (Property::where('property_category_id', $roomCategory->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $roomCategory->delete(); // Attempt deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');
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
    

    public function render()
    {
        return view('livewire.admin.room-categories.view-room-category');
    }
}
