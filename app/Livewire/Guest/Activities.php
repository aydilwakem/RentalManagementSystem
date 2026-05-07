<?php

namespace App\Livewire\Guest;

use App\Models\Activity;
use App\Models\Setting;
use Livewire\Component;

class Activities extends Component
{
    public $activities; 

    public string $companyName = 'Company'; //Default

    public function mount()
    {
        // Fetch the first row of the settings table
        $setting = Setting::first(); // Or use where(...) if you expect multiple rows
        if ($setting) {
            $this->companyName = $setting->company_name;
        }
    }
    
    public function render()
    {
        $this->activities = Activity::all();
        return view('livewire.guest.activities', [
            'activities' => $this->activities,
        ]);
    }
}
