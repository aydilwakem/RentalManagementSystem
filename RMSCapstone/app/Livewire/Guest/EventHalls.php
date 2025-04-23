<?php

namespace App\Livewire\Guest;

use App\Models\Property;
use Livewire\Component;

class EventHalls extends Component
{
    public $eventHalls; 
    public function render()
    {
        $this->eventHalls = Property::ofType('Event Hall')->get();
        return view('livewire.guest.event-halls', [
            'eventHalls' => $this->eventHalls, 
        ]);
    }
}
