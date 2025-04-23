<?php

namespace App\Livewire\Admin\Properties;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;
use Illuminate\Database\QueryException;

#[Layout('layouts.app')]
class ViewProperty extends Component
{
    // Create a public property 
    public Property $property;
    public $house;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal will appear if house is being used by a Tenant

    public function mount(Property $property)
    {
        $this->house = $property->load('category', 'features');
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteHouse()
    {
        if ($this->confirmItemDelete) {
            $property = Property::find($this->confirmItemDelete);

            if ($property) {
                $property->features()->detach();

                $property->delete();

                $this->confirmItemDelete = false;

                session()->flash('message', 'House successfully deleted!');
            } else {
                session()->flash('error', 'House not found!');
            }
        }

        // Redirect to the admin houses page
        return redirect()->route('admin.properties');
    }

    public function render()
    {
        return view('livewire.admin.properties.view-property');
    }
}
