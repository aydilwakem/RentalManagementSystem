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
    public $paymentMethodId;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    //To display info of selected item
    public function mount(PaymentMethod $paymentMethod)
    {
        $this->paymentMethodId = $paymentMethod->id;
        $this->paymentMethod = $paymentMethod;
        $this->mode_of_payment_name = $paymentMethod->mode_of_payment_name;
        $this->account_name = $paymentMethod->account_name;
        $this->account_number = $paymentMethod->account_number;
        $this->mode_of_payment_qr_image = $paymentMethod->mode_of_payment_qr_image;
    }

    public function updatePaymentMethod()
    {
        try{
        $this->validate([
            'mode_of_payment_name' => "required|string|max:255|unique:pm_payment_methods,mode_of_payment_name,{$this->paymentMethodId},id",
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'new_mode_of_payment_qr_image' => 'nullable|image|max:2048', // Ensure image size is within limit
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

        // Ensure the image is uploaded properly
        if ($this->new_mode_of_payment_qr_image && !$this->new_mode_of_payment_qr_image->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Handle Image Upload
        if ($this->new_mode_of_payment_qr_image) {
            if ($this->paymentMethod->mode_of_payment_qr_image) {
                Storage::disk('public')->delete($this->paymentMethod->mode_of_payment_qr_image);
            }

            //save the image in public folder
            $this->mode_of_payment_qr_image = $this->new_mode_of_payment_qr_image->store('payment-methods', 'public');
        }

        // Update Paymet Method
        $this->paymentMethod->update([
            'mode_of_payment_name' => $this->mode_of_payment_name,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'mode_of_payment_qr_image' => $this->mode_of_payment_qr_image,
        ]);

        session()->flash('message', 'Payment Method successfully updated!');

        return redirect()->route('admin.payments');
    }

    public function render()
    {
        return view('livewire.admin.settings.payments.edit-payment');
    }
}
