<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use App\Models\Property;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditTenant extends Component
{

    public Tenant $tenant;
    public $name;
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

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(Tenant $tenant)
    {
        $this->houses = Property::all();
        $this->tenant = $tenant;
        $this->first_name = $tenant->first_name;
        $this->middle_name = $tenant->middle_name;
        $this->last_name = $tenant->last_name;
        $this->suffix = $tenant->suffix;
        $this->house_id = $tenant->house_id;
        $this->email = $tenant->email;
        $this->phone = $tenant->phone;
        $this->birthdate = $tenant->birthdate;
        $this->gender = $tenant->gender;
        $this->occupation = $tenant->occupation;
        $this->notes = $tenant->notes;
    }

    public function updateTenant()
    {
        try {
            $this->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'house_id' => 'required|integer|exists:lt_houses,id',
                'email' => 'required|email|unique:lt_tenants,email,' . $this->tenant->id,
                'phone' => 'required|string|max:20',
                'birthdate' => 'required|date',
                'gender' => 'required|string|in:Male,Female,Other',
                'occupation' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Update Tenant
        $this->tenant->update([
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

        session()->flash('message', 'Tenant successfully updated!');

        return redirect()->route('admin.tenants');
    }

    public function render()
    {
        return view('livewire.admin.tenants.edit-tenant', [
            'houses' => $this->houses,
        ]);
    }
}
