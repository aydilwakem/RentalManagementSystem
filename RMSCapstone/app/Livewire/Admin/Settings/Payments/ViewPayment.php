<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\PaymentMethod;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewPayment extends Component
{
    // Create a public property 
    public PaymentMethod $paymentMethod;
 
    // Function for deleting a record
    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if (!$paymentMethod) {
            session()->flash('error', 'Payment Method not found!');
            return;
        }

        if ($paymentMethod) {
            // Delete the method
            $paymentMethod->delete();

            // Flash success message
            session()->flash('message', 'Payment Method successfully deleted!');

            // Redirect to the admin payments page
            return redirect()->route('admin.payments');
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.payments.view-payment');
    }
}
