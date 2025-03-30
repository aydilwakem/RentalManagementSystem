<?php

namespace App\Livewire\Admin\Properties;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;
use App\Models\HouseCategory;
use App\Models\Tenant;
use Illuminate\Database\QueryException;

#[Layout('layouts.app')]
class ViewProperty extends Component
{

    // Create a public property 
    public Property $property;
    public $houseCategories;

    public $house_category_id;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal will appear if house is being used by a Tenant

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount(Property $property)
    {
        // Load the room with its related category
        $this->property = $property->load('category');
    }



    // Function for deleting a record
    public function deletePropertyItem()
    {
        //find id
        $property = Property::find($this->confirmItemDelete);

        if (!$property) {
            session()->flash('error', 'Property not found!');
            return;
        }

        // Check if the house is referenced in tenant table
        if (Tenant::where('house_id', $property->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
            }

            try{
            $property->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'House successfully deleted!');

            // Redirect to the admin room categories page
            return redirect()->route('admin.properties');

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
        return view('livewire.admin.properties.view-property');
    }
}
