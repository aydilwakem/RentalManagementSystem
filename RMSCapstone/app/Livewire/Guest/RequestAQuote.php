<?php

namespace App\Livewire\Guest;

use App\Mail\EventQuotesMail;
use App\Mail\RequestQuoteMail;
use App\Models\Property;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestAQuote extends Component
{
    // Public declarations
    public $company_name;
    public $contact_person;
    public $email;
    public $contact_number;
    public $event_start;
    public $event_end;
    public $event_type;
    public $other_event_type;
    public $additional_requests;
    public $halls; 
    public $selected_hall; 

    //Branding
    public string $companyName = 'Company'; //Default
    public string $logoPath = ''; 
    public string $companyEmail; 
    public string $companyContact; 
    public string $companyAddress; 
    public string $facebookLink; 
    public string $instagramLink; 

    public function mount(){
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $setting = Setting::first(); // Or use where(...) if you expect multiple rows
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

    public function render()
    {
        return view('livewire.guest.request-a-quote', [
            'halls' => $this->halls
        ]);;
    }

    public function requestQuote()
    {
        //Validate the data
        $this->validate([
        'company_name' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'contact_number' => 'required|string|max:15', // Adjust max length as needed
        'selected_hall' => 'required|exists:properties,id',
        'event_start' => 'required|date|after_or_equal:'.now()->toDateTimeString(),
        'event_end' => 'required|date|after_or_equal:event_start',
        'event_type' => 'required|string',
        'other_event_type' => 'nullable|string',
        'additional_requests' => 'nullable|string|max:500',
    ]);
    
    $hall = Property::find($this->selected_hall);

        $data = [
        'client_company_name' => $this->company_name,
        'contact_person' => $this->contact_person,
        'email' => $this->email,
        'contact_number' => $this->contact_number,
        'selected_hall' => $hall,
        'event_start' => $this->event_start,
        'event_end' => $this->event_end,
        'event_type' => $this->event_type,
        'other_event_type' => $this->other_event_type,
        'additional_requests' => $this->additional_requests,

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
        Log::info('requestQuote method called.');

    // Send email to user
    Mail::to($this->email)->send(new RequestQuoteMail($data));

    // Optional: also send copy to Canopy Farm's internal email
    Mail::to('rmscapstone26@gmail.com')->send(new EventQuotesMail($data));

    // Reset form fields
    $this->reset([
    'company_name', 'contact_person', 'email', 'contact_number', 'event_start',
    'event_end', 'event_type', 'other_event_type', 'additional_requests', 'selected_hall'
    ]);

    session()->flash('message', 'Quote requested successfully! An email of the copy of your responses has been sent.');
    }
}
