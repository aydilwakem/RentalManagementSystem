<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use App\Models\Property;
use Carbon\Carbon;
use Livewire\Component;

class CreateMaintenance extends Component
{
    public $name;
    public $description;
    public $reported_at;
    public $resolved_at;
    public $priority_status = '';
    public $planned_datetime;
    public $property_id;
    public $properties;

    public $confirmCreateItem = false;

    //To show all properties for assignment
    public function mount()
    {
        $this->properties = Property::where('property_type_id', 2)->get();
    }

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function saveMaintenance()
    {
        try {
            // Validate form input
            $this->validate([
                'name' => 'required|string|unique:mnt_maintenance,name',
                'description' => 'required|string',
                'property_id' => 'required|exists:properties,id',
                'reported_at' => 'required|date|after_or_equal:today',
                'resolved_at' => 'nullable|date|after_or_equal:reported_at',
                'planned_datetime' => 'nullable|date|after_or_equal:today',
                'priority_status' => 'required|in:emergency,urgent,routine,planned',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        //For datetime validation
        if ($this->priority_status === 'planned') {
            // Reformat planned_datetime before saving
            $this->planned_datetime = Carbon::parse($this->planned_datetime)->format('Y-m-d H:i:s');
        } else {
            $this->planned_datetime = null;
        }

        // Create Maintenance
        $maintenance = Maintenance::create([
            'name' => $this->name,
            'description' => $this->description,
            'property_id' => $this->property_id,
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
            'planned_datetime' => $this->planned_datetime,
            'priority_status' => $this->priority_status,
        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'property_id', 'reported_at', 'resolved_at', 'priority_status']);

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
