<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Component;

class CreateMaintenance extends Component
{

    public $name;
    public $description;
    public $reported_at;
    public $resolved_at;
    public $priority_status;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }


    public function saveMaintenance()
    {
        try{
        // Validate form input 
        $this->validate([  
            'name' =>  'required|string',
            'description' => 'required|string',
            'reported_at' => 'required|date',
            'resolved_at' => 'nullable|date|after_or_equal:reported_at',
            'priority_status' => 'required|in:emergency,urgent,routine,planned',
        ]);
    }
    catch (\Illuminate\Validation\ValidationException $e) {
                // If validation fails, close the modal
                $this->confirmCreateItem = false;
                throw $e;
            }


        // Create Maintenance
        $maintenance = Maintenance::create([
            'name' => $this->name,
            'description' => $this->description,
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
            'priority_status' => $this->priority_status,
        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'reported_at', 'resolved_at', 'priority_status']);

        // Flash message for success
        session()->flash('message', 'Maintenance successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.maintenances');
    }

    public function render()
    {
        return view('livewire.admin.maintenance.create-maintenance');
    }
}
