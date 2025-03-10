<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\Amenity;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditAmenity extends Component
{
    use WithFileUploads;

    public Amenity $amenity;
    public $name;

    public function mount(Amenity $amenity)
    {
        $this->amenity = $amenity;
        $this->name = $amenity->name;
    }

    public function updateAmenity()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update Amenity
        $this->amenity->update([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Amenity successfully updated!');

        return redirect()->route('admin.amenities');
    }

    public function render()
    {
        return view('livewire.admin.amenities.edit-amenity');
    }
}
