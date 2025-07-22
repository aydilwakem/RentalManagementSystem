<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Component;

class CreateAmenity extends Component
{
    //----------------------- Fields ----------------- //
    public $name;
    public $quantity;
    public $property_feature_type; 
    public $is_active = false;
    
    //---------------------- Modals ------------------ //
    public $confirmCreateItem = false;

    //Method to make modal true
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    /**
     * Save a new amenity after validating the form input.
     * - Validates the amenity name to ensure it is unique and meets the required format.
     * - If validation fails, the modal is closed and an exception is thrown.
     * - Creates a new amenity record in the `PropertyFeature` model.
     * - Resets the form field after saving the amenity.
     * - Displays a success message and redirects to the amenities list page.
     */
    public function saveAmenity()
    {
        try {
            // Validate form input
            $this->validate([
                'name' => 'required|string|max:100',
                'quantity' => 'required|numeric|min:1|max:30',
                'property_feature_type' => 'required|in:appliance,equipment,utility,entertainment,service,fixture',
                'is_active' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create Amenity
        PropertyFeature::create([
            'name' => $this->name,
            'property_type_id' => 1, 
            'quantity' => $this->quantity, 
            'property_feature_type' => $this->property_feature_type, 
            'is_active' => $this->is_active
        ]);

        // Reset form fields
        $this->reset('name', 'quantity', 'is_active', 'property_feature_type', 'quantity');

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
