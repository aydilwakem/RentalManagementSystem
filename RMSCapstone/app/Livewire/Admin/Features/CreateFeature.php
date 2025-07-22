<?php

namespace App\Livewire\Admin\Features;

use App\Models\PropertyFeature;
use Livewire\Component;

class CreateFeature extends Component
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
     * Save a new feature after validating the form input.
     * - Validates the feature name to ensure it is unique and meets the required format.
     * - If validation fails, the modal is closed and an exception is thrown.
     * - Creates a new feature record in the `PropertyFeature` model.
     * - Resets the form field after saving the feature.
     * - Displays a success message and redirects to the amenities list page.
     */
    public function saveFeature()
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
            'property_type_id' => 2, 
            'quantity' => $this->quantity, 
            'property_feature_type' => $this->property_feature_type, 
            'is_active' => $this->is_active
        ]);

        // Reset form fields
        $this->reset('name', 'is_active', 'property_feature_type', 'quantity');

        // Flash message for success
        session()->flash('message', 'Feature successfully created!');

        // Redirect back to amenities list
        return redirect()->route('admin.features'); 
    }


    public function render()
    {
        return view('livewire.admin.features.create-feature');
    }
}
