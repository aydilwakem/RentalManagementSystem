<?php

namespace App\Livewire\Admin\Events;

use Livewire\Component;

class ViewEvent extends Component
{
    public function render()
    {
        return view('livewire.admin.events.view-event');
    }
}
