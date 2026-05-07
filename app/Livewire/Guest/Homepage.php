<?php

namespace App\Livewire\Guest;

use App\Mail\ContactMail;
use App\Mail\ContactUsMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Http\Request;

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
    public $check_in;
    public $check_out;

    public function mount(Request $request)
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

        //Mount default dates
        $this->check_in = $request->query('check_in', date('Y-m-d'));
        $this->check_out = $request->query('check_out', date('Y-m-d', strtotime('+1 day')));

    }

    public function contactUs()
    {
        //Validate the data
        $this->validate([
        'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/', //only letters, space, and hyphens
            ],
        'email' => 'required|email|max:255',
        'contact_number' => [
                'required',
                'string',
                'regex:/^[0-9]{11}$/', //11 digits only
            ],
        'message' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/', //only letters, space, and hyphens
            ],
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

        session()->flash('message', 'Message sent successfully! An email of the copy of your responses has been sent.');
        session()->flash('alert-type', 'success');

        // Reset form fields
        $this->resetForm();

        //Refresh page
        return redirect(request()->header('Referer'));
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->contact_number = '';
        $this->message = '';
    }


    public function render()
    {
        return view('livewire.guest.homepage');
    }
}
