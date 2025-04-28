<?php

namespace App\Livewire\Admin\Features;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewFeature extends Component
{
    public PropertyFeature $feature;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id; // Store ID
    }

    /**
     * Deletes an feature and redirects to the admin amenities page.
     * - Ensures the feature exists before attempting to delete.
     * - Deletes the feature if found.
     * - Flashes a success message to indicate deletion.
     * - Resets the confirmation flag and redirects to the amenities list page.
     */
    public function deleteFeature()
    {
        // Ensure existing ID before deleting
        $feature = PropertyFeature::find($this->confirmItemDelete);

        if (!$feature) {
            session()->flash('error', 'Feature not found!');
            return;
        }

        // Delete feature
        $feature->delete();
        $this->confirmItemDelete = false;

        // Flash success message
        session()->flash('message', 'Feature successfully deleted!');

        // Redirect to the admin features page
        return redirect()->route('admin.features');
    }

    public function render()
    {
        return view('livewire.admin.features.view-feature');
    }
}
