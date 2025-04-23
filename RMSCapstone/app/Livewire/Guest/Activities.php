<?php

namespace App\Livewire\Guest;

use App\Models\Activity;
use Livewire\Component;

class Activities extends Component
{
    public $activities; 
    
    public function render()
    {
        $this->activities = Activity::all();
        return view('livewire.guest.activities', [
            'activities' => $this->activities,
        ]);
    }
}
