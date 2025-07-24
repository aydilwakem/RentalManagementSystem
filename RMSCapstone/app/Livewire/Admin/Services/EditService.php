<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditService extends Component
{
    public Service $service;

    // --------------------------- Fields ------------------------- //
    public $name; 
    public $description;
    public $amount; 
    public $type; 
    public $unit; 
    public $is_active = false;

    // --------------- Modals ------------------ //
    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    // --------------------- Mount Method ------------------------ //
    public function mount(Service $service){
        $this->service = $service; 

        // toggle value
        $this->is_active = (bool) $service->is_active;

        $this->name = $service->name;
        $this->description = $service->description;
        $this->amount = $service->amount;
        $this->type = $service->type;
        $this->unit = $service->unit;   
        
    }

    // ---------------------- Render --------------------- //
    public function render()
    {
        return view('livewire.admin.services.edit-service');
    }

    // ------------------------- Edit Method ------------------------ //
    public function updateService(){
         try {
            // Cast select values to integers to not interfere with select
            $this->is_active = (int) $this->is_active;

            // Validate form input
            $this->validate([
               'name' => 'required|string|max:100|unique:prd_services,name,' . $this->service->id,
                'description' => 'nullable|string|max:255',
                'amount' => 'required|numeric|min:100|max:10000',
                'type' => 'required|in:addon,penalty,package',
                'unit' => 'required|string|max:255',
                'is_active' => 'required|in:0,1',
            
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        //Update the service
        $this->service->update([
            'name' => $this->name,
            'description' => $this->description, 
            'amount' => $this->amount,
            'type' => $this->type,
            'unit' => $this->unit,
            'is_active' => $this->is_active,
        ]); 

        session()->flash('message', 'Service successfully updated!');

        return redirect()->route('admin.services');
    }

}
