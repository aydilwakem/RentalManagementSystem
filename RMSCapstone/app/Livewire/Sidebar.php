<?php

namespace App\Livewire;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public $confirmLogoutItem = false;

    public string $logoPath = ''; 
    public string $companyName = 'Company'; //Default


    public function confirmLogout()
    {
        $this->confirmLogoutItem = true;
    }

    public function logout()
    {
        $this->confirmLogoutItem = false; 

        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function mount()
    {
        // Fetch the first row of the settings table
        $setting = Setting::first(); // Or use where(...) if you expect multiple rows
        if ($setting) {
            $this->companyName = $setting->company_name;
            $this->logoPath = $setting->logo; 
        }
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
