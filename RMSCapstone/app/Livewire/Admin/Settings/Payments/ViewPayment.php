<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewPayment extends Component
{
    //---------------------------------------------- DECLARATIONS ----------------------------------//
    // Create a public property 
    public PaymentMethod $paymentMethod;

    //---------------------------------------------- MODALS ----------------------------------//
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; 

    //---------------------------------------------- MODAL METHOD ----------------------------------//
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
    //---------------------------------------------- DELETE METHOD ----------------------------------//
    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if (!$paymentMethod) {
            session()->flash('error', 'Payment Method not found!');
            return;
        }

        // Check if the method is linked to any transaction
        $usedInTransactions = Payment::whereHas('paymentMethod', function ($query) use ($paymentMethod) {
            $query->where('payment_method_id', $paymentMethod->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete because hall is active in Transactions
            $this->confirmItemDelete = null;
            return;
        }

        // Delete the method
        $paymentMethod->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Payment Method successfully deleted!');
            // Redirect to the admin payments page
            return redirect()->route('admin.payments');
    }
    
//---------------------------------------------- RENDER ----------------------------------//

    public function render()
    {
        return view('livewire.admin.settings.payments.view-payment');
    }
}
