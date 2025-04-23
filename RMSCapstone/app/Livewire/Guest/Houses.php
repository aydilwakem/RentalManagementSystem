<?php

namespace App\Livewire\Guest;

use App\Models\Property;
use Livewire\Component;

class Houses extends Component
{
    public $houses; 
    public function render()
    {
        $this->houses = Property::ofType('House')->get();
        return view('livewire.guest.houses');
    }
}
