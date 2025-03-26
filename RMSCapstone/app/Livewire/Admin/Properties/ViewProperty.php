<?php

namespace App\Livewire\Admin\Properties;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;
use App\Models\HouseCategory;


#[Layout('layouts.app')]
class ViewProperty extends Component
{

    // Create a public property 
    public Property $property;
    public $houseCategories;

    public $house_category_id;

    public $confirmItemDelete = false;

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
    public function deletePropertyItem(Property $property)
    {
        if (!$property) {
            session()->flash('error', 'Property not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            $property->delete();
            $this->confirmItemDelete = false;

            session()->flash('message', 'Property successfully deleted!');
            return redirect()->route('admin.properties');
        }
    }

    public function render()
    {
        return view('livewire.admin.properties.view-property');
    }
}
