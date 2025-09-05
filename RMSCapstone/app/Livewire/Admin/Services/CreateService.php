<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Component;

class CreateService extends Component
{
    // --------------------------- Fields ------------------------- //
    public $name;
    public $description;
    public $amount;
    public $type;
    public $unit;
    public $is_active = '';


    //---------------------- Modals --------------------- //
    public $confirmCreateItem = false;
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    // --------------------- Render ------------------------ //
    public function render()
    {
        return view('livewire.admin.services.create-service');
    }

    // ------------------------ Save Method ------------------------ //
    public function saveService()
    {
        try {
            // Cast select values to integers to not interfere with select
            $this->is_active = (int) $this->is_active;

            // Validate form input
            $validated =  $this->validate([
                'name' => 'required|string|max:100|unique:prd_services,name',
                'description' => 'nullable|string|max:255',
                'amount' => 'required|numeric|min:100|max:10000',
                'type' => 'required|in:addon,penalty,package,food,merchandise',
                'unit' => 'required|string|max:255',
                'is_active' => 'required|in:0,1',

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        //dd($validated);

        //Use the validated variable for create
        Service::create($validated);

        // Reset all form fields
        $this->reset([
            'name',
            'description',
            'amount',
            'type',
            'unit',
            'is_active',
        ]);

        // Flash message for success
        session()->flash('message', 'Service successfully created!');

        // Redirect back to promo list
        return redirect()->route('admin.services');
    }
}
