<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WelcomePage extends Component
{

    public $first_name;
    public $last_name;

    public function mount()
    {
        $user = Auth::user();
        $this->first_name = $user->name;
        $this->last_name = $user->last_name;
    }
    public function render()
    {
        return view('livewire.admin.welcome-page');
    }
}
