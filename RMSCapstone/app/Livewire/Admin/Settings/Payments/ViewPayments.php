<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\PaymentMethod;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewPayments extends Component
{
    use WithPagination;

    #[Url(history:true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $sortBy='created_at';

    #[Url(history:true)]
    public $sortDir='DESC';

    public $confirmItemDelete = false;

    public function confirmDelete($id)
        {
            $this->confirmItemDelete = $id;
        }
    

   public function deletePaymentMethod($id)
    {
        // Find the method by ID
        $paymentMethod = PaymentMethod::find($id);

        if ($paymentMethod) {
            // Delete the payment method
            if ($this->confirmItemDelete) {
                PaymentMethod::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'Payment Method successfully deleted!');
        }
    }
    }

    public function setSortBy($sortByField){

        if($this->sortBy == $sortByField){
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return ;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }
    
    public function render()
    {
        $paymentMethod = PaymentMethod::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
        return view('livewire.admin.settings.payments.view-payments', compact('paymentMethod'));
    }
}
