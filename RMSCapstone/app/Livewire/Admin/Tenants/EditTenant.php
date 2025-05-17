<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\TransactionUser;
use App\Models\Property;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditTenant extends Component
{

    public TransactionUser $tenant;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $email;
    public $contact_number;
    public $company_name;
    public $house_number;
    public $street;
    public $barangay;
    public $city_municipality;
    public $province;
    public $region;
    public $postal_code;
    public $country;

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(TransactionUser $tenant)
    {
        $this->tenant = $tenant;
        $this->first_name = $tenant->first_name;
        $this->middle_name = $tenant->middle_name;
        $this->last_name = $tenant->last_name;
        $this->suffix = $tenant->suffix;
        $this->email = $tenant->email;
        $this->contact_number = $tenant->contact_number;
        $this->company_name = $tenant->company_name;
        $this->house_number = $tenant->house_number;
        $this->street = $tenant->street;
        $this->barangay = $tenant->barangay;
        $this->city_municipality = $tenant->city_municipality;
        $this->province = $tenant->province;
        $this->region = $tenant->region;
        $this->postal_code = $tenant->postal_code;
        $this->country = $tenant->country;
    }

    public function updateTenant()
    {
        try {
            $this->validate([
                'first_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'last_name' => 'required|string|max:100',
                'suffix' => 'nullable|string|max:100',
                'email' => 'required|email|unique:trn_users,email,' . $this->tenant->id, // Exclude the current tenant's email
                'contact_number' => 'nullable|string|max:100',
                'company_name' => 'nullable|string|max:100',
                'city_municipality' => 'nullable|string|max:100',
                'country' => 'required|string|max:100',
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
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'company_name' => $this->company_name,
            'city_municipality' => $this->city_municipality,
            'country' => $this->country,
        ]);

        session()->flash('message', 'Tenant successfully updated!');

        return redirect()->route('admin.tenants');
    }

    public function render()
    {
        return view('livewire.admin.tenants.edit-tenant');
    }
}
