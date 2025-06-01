<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewPayments extends Component
{
    use WithPagination;

    //---------------------------------------------- DECLARATIONS ----------------------------------//
    #[Url(history:true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history:true)]
    public $sortBy='created_at';

    #[Url(history:true)]
    public $sortDir='DESC';

    //---------------------------------------------- MODALS ----------------------------------//
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    //---------------------------------------------- MODAL METHOD ----------------------------------//
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
    
//---------------------------------------------- DELETE PAYMENT METHOD ----------------------------------//
   public function deletePaymentMethod()
    {
        $paymentMethod = PaymentMethod::find($this->confirmItemDelete);

        if (!$paymentMethod) {
            session()->flash('error', 'Payment Method not found.');
            return;
        }

        // Check if the event hall is active in Events
        $usedInTransactions = Payment::whereHas('paymentMethod', function ($query) use ($paymentMethod) {
            $query->where('payment_method_id', $paymentMethod->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Show "Cannot delete" modal
            $this->confirmItemDelete = null; // Reset delete ID
            return;
        }

        try {
            // Delete the method
            $paymentMethod->delete();

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Payment Method successfully deleted!');
            }catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) {
                    $this->cannotDeleteItem = true;
                } else {
                    throw $e;
                }
            }
    }
    
//---------------------------------------------- SORT ----------------------------------//
    public function setSortBy($sortByField){

        if($this->sortBy == $sortByField){
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return ;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }
    
    //---------------------------------------------- RENDER ----------------------------------//
    public function render()
    {
        $paymentMethod = PaymentMethod::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
        return view('livewire.admin.settings.payments.view-payments', compact('paymentMethod'));
    }
}
