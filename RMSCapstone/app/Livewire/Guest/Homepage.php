<?php

namespace App\Livewire\Guest;

use App\Mail\ContactMail;
use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Homepage extends Component
{
    public $name;
    public $email;
    public $contact_number;
    public $message;

    public function contactUs()
    {
        //Validate the data
        $this->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:255',
        'contact_number' => 'required|string|max:15', // Adjust max length as needed
        'message' => 'required|string|max:500',
    ]);
        $data = [
        'name' => $this->name,
        'email' => $this->email,
        'contact_number' => $this->contact_number,
        'message' => $this->message,
    ];

    //Call method
    Log::info('contactUs method called.');

    // Send email to user
    Mail::to($this->email)->send(new ContactUsMail($data));

    // Optional: also send copy to Canopy Farm's internal email
    Mail::to('rmscapstone26@gmail.com')->send(new ContactMail($data));

    // Reset form fields
    $this->reset([
    'name', 'email', 'contact_number', 'message'
    ]);

    session()->flash('message', 'Message sent successfully! An email of the copy of your responses has been sent.');
    }


    public function render()
    {
        return view('livewire.guest.homepage');
    }
}
