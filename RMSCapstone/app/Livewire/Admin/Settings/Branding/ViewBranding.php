<?php

namespace App\Livewire\Admin\Settings\Branding;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class ViewBranding extends Component
{
    use WithFileUploads;

    public $confirmEditItem = false;
    public $company_name;
    public $logo;
    public $image;
    public $newImage;
    public $email;
    public $contact_number;
    public $address;
    public $facebook;
    public $instagram;
    public $terms_and_conditions;
    public $privacy_policy;
    public $refund_policy;
    public $rental_agreement;
    public $custom_css;
    public $custom_js;
    public $enable_deposit_percentage = false;
    public $deposit_percentage;

    public $room_payment_proof_expiration_hours;
    public $event_payment_proof_expiration_hours;
    public $day_tour_payment_proof_expiration_hours;

    public $settings;

    public function mount()
    {
        $this->settings = Setting::first(); // Store in $this->settings

        if ($this->settings) {
            $this->company_name = $this->settings->company_name;
            $this->image = $this->settings->logo;
            $this->email = $this->settings->email;
            $this->contact_number = $this->settings->contact_number;
            $this->address = $this->settings->address;
            $this->facebook = $this->settings->facebook;
            $this->instagram = $this->settings->instagram;
            $this->terms_and_conditions = $this->settings->terms_and_conditions;
            $this->privacy_policy = $this->settings->privacy_policy;
            $this->refund_policy = $this->settings->refund_policy;
            $this->rental_agreement = $this->settings->rental_agreement;
            $this->custom_css = $this->settings->custom_css;
            $this->custom_js = $this->settings->custom_js;
            $this->enable_deposit_percentage = $this->settings->enable_deposit_percentage;
            $this->deposit_percentage = $this->settings->deposit_percentage;
            $this->room_payment_proof_expiration_hours = $this->settings->room_payment_proof_expiration_hours;
            $this->event_payment_proof_expiration_hours = $this->settings->event_payment_proof_expiration_hours;
            $this->day_tour_payment_proof_expiration_hours = $this->settings->day_tour_payment_proof_expiration_hours;
        } else {
            // If no settings exist, create an empty settings instance
            $this->settings = new Setting();
        }
    }

    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }

    public function updateBranding()
    {
        try {
            $this->validate([
                'company_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'newImage' => 'nullable|image|max:2048',
                'email' => 'required|email|max:255',
                'contact_number' => 'nullable|string|max:255|regex:/^[0-9]{11}$/',
                'address' => 'nullable|string|max:255|regex:/^[A-Za-z0-9\s\-,.]+$/',
                'facebook' => 'required|string|max:255',
                'instagram' => 'required|string|max:255',
                'terms_and_conditions' => 'nullable|string',
                'privacy_policy' => 'nullable|string',
                'refund_policy' => 'nullable|string',
                'rental_agreement' => 'nullable|string',
                'custom_css' => 'nullable|string',
                'custom_js' => 'nullable|string',
                'enable_deposit_percentage' => 'boolean',
                'deposit_percentage' => 'nullable|numeric|min:0|max:100',
                'room_payment_proof_expiration_hours' => 'required|integer|min:1',
                'event_payment_proof_expiration_hours' => 'required|integer|min:1',
                'day_tour_payment_proof_expiration_hours' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmEditItem = false;
            throw $e;
        }

        // Ensure settings exist
        if (!$this->settings->exists) {
            $this->settings = Setting::firstOrCreate([]);
        }

        // Handle Image Upload
        if ($this->newImage) {
            if ($this->settings->logo) { // Use correct column name
                Storage::disk('public')->delete($this->settings->logo);
            }

            // Save the image in public folder
            $imagePath = $this->newImage->store('branding', 'public');
            $this->settings->logo = $imagePath;
        }

        // Update settings in database
        $this->settings->update([
            'company_name' => $this->company_name,
            'logo' => $this->settings->logo, // Ensure the new logo path is stored
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'terms_and_conditions' => $this->terms_and_conditions,
            'privacy_policy' => $this->privacy_policy,
            'refund_policy' => $this->refund_policy,
            'rental_agreement' => $this->rental_agreement,
            'enable_deposit_percentage' => $this->enable_deposit_percentage ? 1 : 0,
            'deposit_percentage' => $this->deposit_percentage,
            'room_payment_proof_expiration_hours' => $this->room_payment_proof_expiration_hours,
            'event_payment_proof_expiration_hours' => $this->event_payment_proof_expiration_hours,
            'day_tour_payment_proof_expiration_hours' => $this->day_tour_payment_proof_expiration_hours,
            'custom_css' => $this->custom_css,
            'custom_js' => $this->custom_js,
        ]);

        session()->flash('message', 'Branding successfully updated!');
        return redirect()->route('admin.branding');
    }



    public function render()
    {
        return view('livewire.admin.settings.branding.view-branding', [
            'settings' => $this->settings,
        ]);
    }
}
