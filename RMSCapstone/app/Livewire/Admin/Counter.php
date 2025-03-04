<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Counter extends Component
{

    public $count = "tite";

    public function render()
    {
        return view('livewire.admin.counter');
    }
}
