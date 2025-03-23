<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\Amenity;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewAmenity extends Component
{
    // Create a public property
    public Amenity $amenity;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function for deleting a record
    public function deleteAmenity(Amenity $amenity)
    {
        if (!$amenity) {
            session()->flash('error', 'Amenity not found!');
            return;
        }

        // Delete the amenity
        if ($this->confirmItemDelete) {
            $amenity->delete();
            $this->confirmItemDelete = false;

        // Flash success message
        session()->flash('message', 'Amenity successfully deleted!');

        // Redirect to the admin amenities page
        return redirect()->route('admin.amenities');
    }
}

    public function render()
    {
        return view('livewire.admin.amenities.view-amenity');
    }
}
