<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class RequestAQuote extends Component
{
    public function render()
    {
        return view('livewire.guest.request-a-quote');
    }

    public function requestQuote()
    {
        Log::info('requestQuote method called.');

        // Create email to send to canopy farm's email
    }
}
