<?php

namespace App\Livewire\Admin\Properties;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Property;
use App\Models\Transaction;
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
        $house = Property::find($this->confirmItemDelete);

        if (!$house) {
            session()->flash('error', 'House not found!');
            return redirect()->route('admin.properties');
        }

        // Check if the event hall is linked to any transaction
        $usedInTransactions = Transaction::whereHas('properties', function ($query) use ($house) {
            $query->where('property_id', $house->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete because hall is active in Transactions
            $this->confirmItemDelete = null;
            return;
        }

        // Detach all features/amenities
        $house->features()->detach();

        // Delete the hall
        $house->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'House successfully deleted!');
        }

        return redirect()->route('admin.properties');
    }

    public function render()
    {
        return view('livewire.admin.properties.view-property');
    }
}
