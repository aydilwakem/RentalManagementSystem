<?php

namespace App\Livewire\Admin\HouseCategories;

use App\Models\HouseCategory;
use App\Models\Property;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewHouseCategory extends Component
{
    // Create a public property
    public HouseCategory $houseCategory;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function for deleting a record
    public function deleteHouseCategory()
    {
        //find id
        $houseCategory = HouseCategory::find($this->confirmItemDelete);

        if (!$houseCategory) {
            session()->flash('error', 'Room Category not found!');
            return;
        }


            // Check if the category is referenced in another table
            if (Property::where('house_category_id', $houseCategory->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
            }

            try{
            $houseCategory->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'House Category successfully deleted!');

            // Redirect to the admin room categories page
            return redirect()->route('admin.house-categories');

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
        return view('livewire.admin.house-categories.view-house-category');
    }
}
