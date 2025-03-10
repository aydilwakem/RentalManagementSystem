<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\Amenity;
use Livewire\Component;

class CreateAmenity extends Component
{
    public $name;

    public function saveAmenity()
    {
        // Validate form input
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create Amenity
        Amenity::create([
            'name' => $this->name,
        ]);

        // Reset form fields
        $this->reset('name');

        // Flash message for success
        session()->flash('message', 'Amenity successfully created!');

        // Redirect back to amenities list
        return redirect()->route('admin.amenities');
    }

    public function render()
    {
        return view('livewire.admin.amenities.create-amenity');
    }
}
