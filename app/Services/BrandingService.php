<?php

namespace App\Services;

use App\Models\Setting;

class BrandingService
{
    public function getBrandingData(): array
    {
        $setting = Setting::first();

        return [
            'branding_company_name' => $setting->company_name ?? 'Your Company',
            'logo_path' => $setting->logo ?? '',
            'branding_company_email' => $setting->email ?? '',
            'branding_company_contact' => $setting->contact_number ?? '',
            'company_address' => $setting->address ?? '',
            'facebook_link' => $setting->facebook ?? '',
            'instagram_link' => $setting->instagram ?? '',
            'enable_deposit_percentage' => $setting->enable_deposit_percentage ?? '',
            'deposit_percentage' => $setting->deposit_percentage ?? 0,
        ];
    }
}
