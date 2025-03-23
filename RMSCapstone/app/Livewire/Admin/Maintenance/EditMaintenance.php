<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditMaintenance extends Component
{

    public Maintenance $maintenance;
    public $name;
    public $description;
    public $reported_at;
    public $resolved_at;
    public $priority_status;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }




    //To display info of selected item
    public function mount(Maintenance $maintenance)
    {
        $this->name = $maintenance->name;
        $this->description = $maintenance->description;
        $this->reported_at = optional($maintenance->reported_at)->format('Y-m-d');
        $this->resolved_at = optional($maintenance->resolved_at)->format('Y-m-d');
        $this->priority_status = $maintenance->priority_status;
    }

    public function updateMaintenance()
    {
        try{
        // Validate form input 
        $this->validate([   
            'name' => 'required|string',
            'description' => 'required|string',
            'reported_at' => 'required|date',
            'resolved_at' => 'nullable|date|after_or_equal:reported_at',
            'priority_status' => 'required|in:emergency,urgent,routine,planned',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }


        // Update Event Hall
        $this->maintenance->update([
            'name' => $this->name,
            'description' => $this->description,
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
            'priority_status' => $this->priority_status,
        ]);

        session()->flash('message', 'Maintenance item successfully updated!');

        return redirect()->route('admin.maintenances');
    }


    public function render()
    {
        return view('livewire.admin.maintenance.edit-maintenance');
    }
}
