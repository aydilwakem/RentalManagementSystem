<?php

namespace App\Livewire\Admin\Features;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditFeature extends Component
{
    //Public variable declarations of features
    public PropertyFeature $feature;
    public $name;
    public $quantity;
    public $property_feature_type; 
    public $is_active = false;
    public $featureId;
    public $property_type_id; 

    //Public declaration of modal
    public $confirmEditItem = false;

    //Method to make modal true by getting id
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    //Method to fetch all features
    public function mount(PropertyFeature $feature)
    {
        $this->feature = $feature;
        $this->featureId = $feature->id;
        $this->name = $feature->name;
        $this->quantity = $feature->quantity; 
        $this->property_feature_type = $feature->property_feature_type;
        $this->is_active = $feature->is_active;  
    }

    /**
     * Updates an existing feature after validating the form input.
     * - Validates the feature name to ensure it is unique and meets the required format, excluding the current featureity.
     * - If validation fails, the modal is closed and an exception is thrown.
     * - Updates the feature with the new name in the `PropertyFeature` model.
     * - Displays a success message after the update is successful.
     * - Redirects to the featureities list page after the update.
     */
    public function updateFeature()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:100|regex:/^[A-Za-z\s\-]+$/',
                'quantity' => 'required|numeric|min:1|max:30',
                'property_feature_type' => 'required|in:appliance,equipment,utility,entertainment,service,fixture',
                'is_active' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Update featureity
        $this->feature->update([
            'name' => $this->name,
            'property_type_id' => 2, 
            'quantity' => $this->quantity, 
            'property_feature_type' => $this->property_feature_type, 
            'is_active' => $this->is_active
        ]);

        session()->flash('message', 'Feature successfully updated!');

        return redirect()->route('admin.features');
    }

    public function render()
    {
        return view('livewire.admin.features.edit-feature');
    }
}
