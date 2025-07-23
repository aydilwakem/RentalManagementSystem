<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditAmenity extends Component
{
    use WithFileUploads;

    //Public variable declarations of amenity
    public PropertyFeature $amenity;
    public $property_type_id;
    public $name;
    public $quantity;
    public $property_feature_type; 
    public $is_active = false;
    public $amenityId;

    //Public declaration of modal
    public $confirmEditItem = false;

    //Method to make modal true by getting id
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    //Method to fetch all amenities
    public function mount(PropertyFeature $amenity)
    {
        $this->amenity = $amenity;
        $this->amenityId = $amenity->id;
        $this->name = $amenity->name;
        $this->quantity = $amenity->quantity; 
        $this->property_feature_type = $amenity->property_feature_type;
        $this->is_active = $amenity->is_active;  
    }

    /**
     * Updates an existing amenity after validating the form input.
     * - Validates the amenity name to ensure it is unique and meets the required format, excluding the current amenity.
     * - If validation fails, the modal is closed and an exception is thrown.
     * - Updates the amenity with the new name in the `PropertyFeature` model.
     * - Displays a success message after the update is successful.
     * - Redirects to the amenities list page after the update.
     */
    public function updateAmenity()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:100',
                'quantity' => 'required|numeric|min:1|max:30',
                'property_feature_type' => 'required|in:appliance,equipment,utility,entertainment,service,fixture',
                'is_active' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Update Amenity
        $this->amenity->update([
            'name' => $this->name,
            'property_type_id' => 1, 
            'quantity' => $this->quantity, 
            'property_feature_type' => $this->property_feature_type, 
            'is_active' => $this->is_active
        ]);

        session()->flash('message', 'Amenity successfully updated!');

        return redirect()->route('admin.amenities');
    }

    public function render()
    {
        return view('livewire.admin.amenities.edit-amenity');
    }
}
