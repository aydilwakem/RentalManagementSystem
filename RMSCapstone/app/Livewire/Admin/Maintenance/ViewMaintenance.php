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

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
    // Function for deleting a record
    public function deleteMaintenanceItem(Maintenance $maintenance)
    {
    if (!$maintenance) {
        session()->flash('error', 'Maintenance not found!');
        return;
    }

    if ($this->confirmItemDelete) {
        $maintenance->delete();
        $this->confirmItemDelete = false;

        session()->flash('message', 'Maintenance successfully deleted!');
        return redirect()->route('admin.maintenances');
    }
    }

    public function render()
    {
        return view('livewire.admin.maintenance.view-maintenance');
    }
}
