<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NoAccess extends Component
{
    // Redirects user to dashboard once user has a role
    public function mount()
    {
       if (Auth::check() && Auth::user()->roles->isNotEmpty()) {
        return redirect()->route('dashboard');
    }
    }

    public function render()
    {
        return view('livewire.no-access');
    }
}
