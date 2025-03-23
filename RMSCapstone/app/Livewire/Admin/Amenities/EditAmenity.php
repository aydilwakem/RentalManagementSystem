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

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(Amenity $amenity)
    {
        $this->amenity = $amenity;
        $this->name = $amenity->name;
    }

    public function updateAmenity()
    {
        try{
        $this->validate([
            'name' => 'required|string|max:255',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
                // If validation fails, close the modal
                $this->confirmEditItem = false;
                throw $e;
            }

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
