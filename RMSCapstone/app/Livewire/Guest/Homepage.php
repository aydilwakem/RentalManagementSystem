<?php

namespace App\Livewire\Guest;

use App\Mail\ContactMail;
use App\Mail\ContactUsMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Homepage extends Component
{
    public $name;
    public $email;
    public $contact_number;
    public $message;

    public string $companyName = 'Company'; //Default
    public string $logoPath = ''; 
    public string $companyEmail; 
    public string $companyContact; 
    public string $companyAddress; 
    public string $facebookLink; 
    public string $instagramLink; 

    public function mount()
    {
        //For Branding
        // Fetch the first row of the settings table
        $setting = Setting::first(); 
        if ($setting) {
            $this->companyName = $setting->company_name;
            $this->logoPath = $setting->logo;
            $this->companyEmail = $setting->email;
            $this->companyContact = $setting->contact_number;
            $this->companyAddress = $setting->address;
            $this->facebookLink = $setting->facebook;
            $this->instagramLink = $setting->instagram;
        }
    }
    
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

        // Branding
        'company_name' => $this->companyName,
        'logo_path' => $this->logoPath,
        'company_email' => $this->companyEmail,
        'company_contact' => $this->companyContact,
        'company_address' => $this->companyAddress,
        'facebook_link' => $this->facebookLink,
        'instagram_link' => $this->instagramLink,
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
