<?php

namespace App\Livewire\Admin\Inclusions;

use App\Models\PropertyFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewInclusion extends Component
{
    public PropertyFeature $inclusion;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id; // Store ID
    }

    /**
     * Deletes an inclusion and redirects to the admin inclusion page.
     * - Ensures the inclusion exists before attempting to delete.
     * - Deletes the inclusion if found.
     * - Flashes a success message to indicate deletion.
     * - Resets the confirmation flag and redirects to the inclusion list page.
     */
    public function deleteInclusion()
    {
        // Ensure existing ID before deleting
        $inclusion = PropertyFeature::find($this->confirmItemDelete);

        if (!$inclusion) {
            session()->flash('error', 'Inclusion not found!');
            return;
        }

        // Delete feature
        $inclusion->delete();
        $this->confirmItemDelete = false;

        // Flash success message
        session()->flash('message', 'Inclusion successfully deleted!');

        // Redirect to the admin inclusions page
        return redirect()->route('admin.inclusions');
    }
    
    public function render()
    {
        return view('livewire.admin.inclusions.view-inclusion');
    }
}
