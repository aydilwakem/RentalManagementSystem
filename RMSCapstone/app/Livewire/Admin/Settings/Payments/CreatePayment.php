<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreatePayment extends Component
{
    use WithFileUploads;

    public $mode_of_payment_name;
    public $account_name;
    public $account_number;
    public $mode_of_payment_qr_image;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function savePaymentMethod()
    {
        try{
        // Validate form input (including image)
        $this->validate([
            'mode_of_payment_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/|unique:pm_payment_methods,mode_of_payment_name',
            'account_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
            'account_number' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
            'mode_of_payment_qr_image' => 'nullable|image|max:1024', // Max 1MB image
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }

        // Ensure image upload is complete before storing
        if ($this->mode_of_payment_qr_image && !$this->mode_of_payment_qr_image->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Store Image (if uploaded)
        $imagePath = null;
        if ($this->mode_of_payment_qr_image) {
            $imagePath = $this->mode_of_payment_qr_image->store('payment-methods', 'public'); // Saves in storage/app/public/event-halls
        }

        // Create Event Category
        $paymentMethod = PaymentMethod::create([
            'mode_of_payment_name' => $this->mode_of_payment_name,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'mode_of_payment_qr_image' => $imagePath, // Save path in DB
        ]);

        // Reset form fields
        $this->reset(['mode_of_payment_name', 'account_name', 'account_number', 'mode_of_payment_qr_image']);

        // Flash message for success
        session()->flash('message', 'Payment Method successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.payments');
    }
    
    public function render()
    {
        return view('livewire.admin.settings.payments.create-payment');
    }
}
