<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app')]
class EditPayment extends Component
{
    use WithFileUploads;

    public PaymentMethod $paymentMethod;

    public $mode_of_payment_name;
    public $account_name;
    public $account_number;
    public $mode_of_payment_qr_image;
    public $new_mode_of_payment_qr_image;

    public $confirmEditItem = false;
    public $confirmDeleteImage = false;

    public function mount(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->mode_of_payment_name = $paymentMethod->mode_of_payment_name;
        $this->account_name = $paymentMethod->account_name;
        $this->account_number = $paymentMethod->account_number;
        $this->mode_of_payment_qr_image = $paymentMethod->mode_of_payment_qr_image;
    }

    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }

    public function updatePaymentMethod()
    {
        $this->validate([
            'mode_of_payment_name' => "required|string|max:255|unique:pm_payment_methods,mode_of_payment_name,{$this->paymentMethod->id}",
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'new_mode_of_payment_qr_image' => 'nullable|image|max:2048',
        ]);

        if ($this->new_mode_of_payment_qr_image) {

            // delete old file if exists
            if ($this->mode_of_payment_qr_image) {
                Storage::disk('public')->delete($this->mode_of_payment_qr_image);
            }

            // upload new image
            $this->mode_of_payment_qr_image = $this->new_mode_of_payment_qr_image->store('payment-methods', 'public');
        }

        $this->paymentMethod->update([
            'mode_of_payment_name' => $this->mode_of_payment_name,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'mode_of_payment_qr_image' => $this->mode_of_payment_qr_image,
        ]);

        session()->flash('message', 'Payment Method updated successfully!');
        return redirect()->route('admin.payments');
    }

    public function confirmDeleteImage()
    {
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
    {
        if ($this->mode_of_payment_qr_image) {
            Storage::disk('public')->delete($this->mode_of_payment_qr_image);
        }

        $this->paymentMethod->update(['mode_of_payment_qr_image' => null]);

        $this->mode_of_payment_qr_image = null;
        $this->new_mode_of_payment_qr_image = null;
        $this->confirmDeleteImage = false;
    }

    public function render()
    {
        return view('livewire.admin.settings.payments.edit-payment');
    }
}
