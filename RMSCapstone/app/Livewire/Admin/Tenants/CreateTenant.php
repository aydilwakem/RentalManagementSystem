<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use App\Models\Property;

class CreateTenant extends Component
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $house_id;
    public $email;
    public $phone;
    public $birthdate;
    public $gender;
    public $occupation;
    public $notes;

    public $houses;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        $this->houses = Property::all();
    }

    public function saveTenant()
    {
        try {
            // Validate form input
            $this->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'house_id' => 'required|integer|exists:lt_houses,id',
                'email' => 'required|email|unique:lt_tenants,email',
                'phone' => 'required|string|max:20',
                'birthdate' => 'required|date',
                'gender' => 'required|string|in:Male,Female,Other',
                'occupation' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create Tenant
        Tenant::create([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'house_id' => $this->house_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'occupation' => $this->occupation,
            'notes' => $this->notes,
        ]);

        // Reset form fields
        $this->reset(['first_name', 'middle_name', 'last_name', 'suffix', 'house_id', 'email', 'phone', 'birthdate', 'gender', 'occupation', 'notes']);

        // Flash message for success
        session()->flash('message', 'Tenant successfully created!');

        // Redirect back to tenants list
        return redirect()->route('admin.tenants');
    }

    public function render()
    {
        return view('livewire.admin.tenants.create-tenant', [
            'houses' => $this->houses,
        ]);
    }
}
