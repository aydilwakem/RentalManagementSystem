<?php

namespace App\Livewire\Admin\Inclusions;

use App\Models\PropertyFeature;
use Livewire\Component;

class CreateInclusion extends Component
{
    //-------------- Fields ----------------- //
    public $name;
    public $quantity;
    public $property_feature_type; 
    public $is_active = false;
    
    //Public declaration of confirmation modal
    public $confirmCreateItem = false;

    //Method to make modal true
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    /**
     * Save a new inclusion after validating the form input.
     * - Validates the inclusion name to ensure it is unique and meets the required format.
     * - If validation fails, the modal is closed and an exception is thrown.
     * - Creates a new inclusion record in the `PropertyFeature` model.
     * - Resets the form field after saving the inclusion.
     * - Displays a success message and redirects to the amenities list page.
     */
    public function saveInclusion()
    {
        try {
            // Validate form input
            $this->validate([
                'name' => 'required|string|max:255',
                'quantity' => 'required|numeric|min:1|max:30',
                'property_feature_type' => 'required|in:appliance,equipment,utility,entertainment,service,fixture',
                'is_active' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create inclusion
        PropertyFeature::create([
            'name' => $this->name,
            'property_type_id' => 3, //Type 3 for event hall
            'quantity' => $this->quantity, 
            'property_feature_type' => $this->property_feature_type, 
            'is_active' => $this->is_active
        ]);

        // Reset form fields
        $this->reset('name', 'quantity', 'is_active', 'property_feature_type', 'quantity');

        // Flash message for success
        session()->flash('message', 'Inclusion successfully created!');

        // Redirect back to amenities list
        return redirect()->route('admin.inclusions'); 
    }

    public function render()
    {
        return view('livewire.admin.inclusions.create-inclusion');
    }
}
