<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use App\Models\Property;
use Carbon\Carbon;
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
    public $maintenanceId;
    public $planned_datetime;
    public $property_id;
    public $properties;

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }




    //To display info of selected item
    public function mount(Maintenance $maintenance)
    {
        $this->name = $maintenance->name;
        $this->maintenanceId = $maintenance->id;
        $this->property_id = $maintenance->property_id;
        $this->description = $maintenance->description;
        $this->reported_at = optional($maintenance->reported_at)->format('Y-m-d');
        $this->resolved_at = optional($maintenance->resolved_at)->format('Y-m-d');
        $this->planned_datetime = $maintenance->planned_datetime
        ? Carbon::parse($maintenance->planned_datetime)->format('Y-m-d\TH:i')
        : null;
        $this->priority_status = $maintenance->priority_status;

        //to show properties
        $this->properties = Property::where('property_type_id', 2)->get();
    }

    public function updateMaintenance()
    {
        try{
        // Validate form input
        $this->validate([
            'name' => "required|string|unique:mnt_maintenance,name,{$this->maintenanceId},id",
            'property_id' => 'required|exists:properties,id',
            'description' => 'required|string',
            'reported_at' => 'required|date',
            'resolved_at' => 'nullable|date|after_or_equal:reported_at',
            'planned_datetime' =>'nullable|date|after_or_equal:today',
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
            'property_id' => $this->property_id,
            'description' => $this->description,
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
            'planned_datetime' => $this->planned_datetime,
            'priority_status' => $this->priority_status,
        ]);

        // Check if 'resolved_at' is set and if so, add a specific session message
    if ($this->resolved_at) {
        session()->flash('message', 'Maintenance successfully resolved! Moved to Old Maintenances');
    } else {
        session()->flash('message', 'Maintenance item successfully updated!');
    }

        return redirect()->route('admin.maintenances');
    }


    public function render()
    {
        return view('livewire.admin.maintenance.edit-maintenance');
    }
}
