<?php

namespace App\Livewire\Guest;

use App\Models\Setting;
use Livewire\Component;

class Footer extends Component
{
    public string $companyName = 'Company'; //Default
    public string $email; 
    public string $contactNumber; 
    public string $address; 
    public string $facebookLink; 
    public string $instagramLink; 

    public function mount()
    {
        // Fetch the first row of the settings table
        $setting = Setting::first(); 
        if ($setting) {
            $this->companyName = $setting->company_name;
            $this->email = $setting->email; 
            $this->contactNumber = $setting->contact_number; 
            $this->address = $setting->address;
            $this->facebookLink = $setting->facebook; 
            $this->instagramLink = $setting->instagram; 
        }
    }
    
    public function render()
    {
        return view('livewire.guest.footer');
    }
}
