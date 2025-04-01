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
    public $settings;

    public function mount()
    {
        $settings = Setting::first();

        if ($settings) {
            $this->company_name = $settings->company_name;
            $this->logo = $settings->logo;
            $this->email = $settings->email;
            $this->contact_number = $settings->contact_number;
            $this->address = $settings->address;
            $this->facebook = $settings->facebook;
            $this->instagram = $settings->instagram;
            $this->terms_and_conditions = $settings->terms_and_conditions;
            $this->privacy_policy = $settings->privacy_policy;
            $this->refund_policy = $settings->refund_policy;
            $this->rental_agreement = $settings->rental_agreement;
            $this->custom_css = $settings->custom_css;
            $this->custom_js = $settings->custom_js;
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
                'company_name' => 'required|string|max:255',
                'newImage' => 'nullable|image|max:2048',
                'email' => 'required|email|max:255',
                'contact_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'instagram' => 'nullable|string|max:255',
                'terms_and_conditions' => 'nullable|string',
                'privacy_policy' => 'nullable|string',
                'refund_policy' => 'nullable|string',
                'rental_agreement' => 'nullable|string',
                'custom_css' => 'nullable|string',
                'custom_js' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmEditItem = false;
            throw $e;
        }

        // Use firstOrCreate to ensure settings exist
        $settings = Setting::firstOrCreate([]);  // Creates an entry if none exists

        // Ensure the image is uploaded properly
        if ($this->newImage && !$this->newImage->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Handle Image Upload
        if ($this->newImage) {
            // Delete the old image if it exists
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }

            // Save the new image in the public folder
            $logoPath = $this->newImage->store('logos', 'public');
        } else {
            // Retain the old logo if no new image is uploaded
            $logoPath = $settings->logo;
        }

        // Update settings
        $settings->update([
            'company_name' => $this->company_name,
            'logo' => $logoPath,  // Use $logoPath instead of $this->logo
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'terms_and_conditions' => $this->terms_and_conditions,
            'privacy_policy' => $this->privacy_policy,
            'refund_policy' => $this->refund_policy,
            'rental_agreement' => $this->rental_agreement,
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
