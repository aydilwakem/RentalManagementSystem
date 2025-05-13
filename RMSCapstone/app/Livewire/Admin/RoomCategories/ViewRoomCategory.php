<?php

namespace App\Livewire\Admin\RoomCategories;

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
    public function deleteCategory()
    {
        //find id
        $roomCategory = PropertyCategory::find($this->confirmItemDelete);

        if (!$roomCategory) {
            session()->flash('error', 'Room Category not found!');
            return;
        }

        if ($roomCategory) {
            $roomCategory->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');

            // Redirect to the admin room categories page
            return redirect()->route('admin.room-categories');
    }
    }
    

    public function render()
    {
        return view('livewire.admin.room-categories.view-room-category');
    }
}
