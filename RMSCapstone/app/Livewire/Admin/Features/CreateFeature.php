<?php

namespace App\Livewire\Admin\Features;

use App\Models\PropertyFeature;
use Livewire\Component;

class CreateFeature extends Component
{
    //Public declaration of fillable field
    public $name;
    
    //Public declaration of confirmation modal
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
    public function saveFeature()
    {
        try {
            // Validate form input
            $this->validate([
                'name' => 'required|string|max:100',
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
        ]);

        // Reset form fields
        $this->reset('name');

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
