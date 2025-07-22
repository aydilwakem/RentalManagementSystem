<?php

namespace App\Livewire\Admin\Inclusions;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditInclusion extends Component
{
    //Public variable declarations of inclusion
    public PropertyFeature $inclusion;
    public $name;
    public $inclusionId;
    public $property_type_id; 
    public $quantity;
    public $property_feature_type; 
    public $is_active = false;

    //Public declaration of modal
    public $confirmEditItem = false;

    //Method to make modal true by getting id
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    //Method to fetch all inclusion
    public function mount(PropertyFeature $inclusion)
    {
        $this->inclusion = $inclusion;
        $this->inclusionId = $inclusion->id;
        $this->name = $inclusion->name;
        $this->quantity = $inclusion->quantity; 
        $this->property_feature_type = $inclusion->property_feature_type;
        $this->is_active = $inclusion->is_active; 
    }

    /**
     * Updates an existing inclusion after validating the form input.
     * - Validates the inclusion name to ensure it is unique and meets the required format, excluding the current featureity.
     * - If validation fails, the modal is closed and an exception is thrown.
     * - Updates the inclusion with the new name in the `PropertyFeature` model.
     * - Displays a success message after the update is successful.
     * - Redirects to the inclusion list page after the update.
     */
    public function updateInclusion()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
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
        $this->inclusion->update([
            'name' => $this->name,
            'property_type_id' => 3, //Type 3 for event hall 
            'quantity' => $this->quantity, 
            'property_feature_type' => $this->property_feature_type, 
            'is_active' => $this->is_active
        ]);

        session()->flash('message', 'Inclusion successfully updated!');

        return redirect()->route('admin.inclusions');
    }
    
    public function render()
    {
        return view('livewire.admin.inclusions.edit-inclusion');
    }
}
