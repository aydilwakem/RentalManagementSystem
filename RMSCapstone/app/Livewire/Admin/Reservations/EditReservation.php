<?php

namespace App\Livewire\Admin\Reservations;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class EditReservation extends Component
{
    public function render()
    {
        return view('livewire.admin.reservations.edit-reservation');
    }
}
