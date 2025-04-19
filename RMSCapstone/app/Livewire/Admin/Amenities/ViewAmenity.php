<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewAmenity extends Component
{
    public PropertyFeature $amenity;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id; // Store ID
    }

    public function deleteAmenity()
    {
        // Ensure existing ID before deleting
        $amenity = PropertyFeature::find($this->confirmItemDelete);

        if (!$amenity) {
            session()->flash('error', 'Amenity not found!');
            return;
        }

        // Delete amenity
        $amenity->delete();
        $this->confirmItemDelete = false;

        // Flash success message
        session()->flash('message', 'Amenity successfully deleted!');

        // Redirect to the admin amenities page
        return redirect()->route('admin.amenities');
    }

    public function render()
    {
        return view('livewire.admin.amenities.view-amenity');
    }
}
