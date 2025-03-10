<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewMaintenance extends Component
{
    // Create a public property 
    public Maintenance $maintenance;
 
    // Function for deleting a record
    public function deleteMaintenanceItem(Maintenance $maintenance)
    {
        if (!$maintenance) {
            session()->flash('error', 'Maintenance item not found!');
            return;
        }

        if ($maintenance) {
            // Delete the event hall
            $maintenance->delete();

            // Flash success message
            session()->flash('message', 'Maintenance item successfully deleted!');

            // Redirect to the admin event categories page
            return redirect()->route('admin.maintenances');
        }
    }

    public function render()
    {
        return view('livewire.admin.maintenance.view-maintenance');
    }
}
