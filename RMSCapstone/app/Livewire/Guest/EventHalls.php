<?php

namespace App\Livewire\Guest;

use App\Models\Property;
use App\Models\Setting;
use Livewire\Component;

class EventHalls extends Component
{
    public $eventHalls; 

    public string $companyName = 'Company'; //Default
    public string $email; 
    public string $contactNumber; 

    public function mount()
    {
        // Fetch the first row of the settings table
        $setting = Setting::first(); // Or use where(...) if you expect multiple rows
        if ($setting) {
            $this->companyName = $setting->company_name;
            $this->email = $setting->email; 
            $this->contactNumber = $setting->contact_number; 
        }
    }
    
    public function render()
    {
        $this->eventHalls = Property::ofType('Event Hall')->get();
        return view('livewire.guest.event-halls', [
            'eventHalls' => $this->eventHalls, 
        ]);
    }
}
