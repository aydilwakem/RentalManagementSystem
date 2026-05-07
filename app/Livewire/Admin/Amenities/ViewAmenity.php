<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewAmenity extends Component
{
    public PropertyFeature $amenity;
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id; // Store ID
    }

    /**
     * Deletes an amenity and redirects to the admin amenities page.
     * - Ensures the amenity exists before attempting to delete.
     * - Deletes the amenity if found.
     * - Flashes a success message to indicate deletion.
     * - Resets the confirmation flag and redirects to the amenities list page.
     */
    public function deleteAmenity()
    {
        // Ensure existing ID before deleting
        $amenity = PropertyFeature::find($this->confirmItemDelete);

        if (!$amenity) {
            session()->flash('error', 'Amenity not found!');
            return;
        }

        if ($amenity->is_active) {
            $this->cannotDeleteItem = true;
            $this->confirmItemDelete = null;
            return;
        }

        // Delete amenity
        $amenity->delete();
        $this->confirmItemDelete = false;

        // Flash success message
        session()->flash('message', 'Amenity successfully deleted!');

        // Redirect to the admin amenities page
        return redirect()->route('admin.amenities');
    }

    public function render()
    {
        return view('livewire.admin.amenities.view-amenity');
    }
}
