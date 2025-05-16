<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\TransactionUser;
use Livewire\Component;
use App\Models\Property;

class CreateTenant extends Component
{

    public $trn_user_type = 'tenant'; // Default value set to 'tenant'
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



    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function saveTenant()
    {
        try {
            // Validate form input
            $this->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:255',
                'email' => 'required|email|unique:trn_users,email',
                'contact_number' => 'nullable|string|max:100',
                'company_name' => 'nullable|string|max:255',
                'city_municipality' => 'nullable|string|max:255',
                'country' => 'required|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create Tenant
        TransactionUser::create([
            'trn_user_type' => $this->trn_user_type,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'email' => $this->email,
            'company_name' => $this->company_name,
            'contact_number' => $this->contact_number,
            'city_municipality' => $this->city_municipality,
            'country' => $this->country,
        ]);

        // Reset form fields
        $this->reset(['trn_user_type', 'first_name', 'middle_name', 'last_name', 'suffix',  'contact_number', 'company_name', 'email', 'city_municipality', 'country']);

        // Flash message for success
        session()->flash('message', 'Tenant successfully created!');

        // Redirect back to tenants list
        return redirect()->route('admin.tenants');
    }

    public function render()
    {
        return view('livewire.admin.tenants.create-tenant');
    }
}
