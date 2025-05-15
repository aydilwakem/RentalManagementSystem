<?php

namespace App\Livewire\Admin\Reservations\Payments;

use Livewire\Component;
use App\Models\Payment;

class PaymentList extends Component
{

    public $payments;
    public $transactionUser;
    public $invoice;
    public $transaction;

    public function render()
    {

        return view('livewire.admin.reservations.payments.payment-list');
    }

    public function mount()
    {
        $this->payments = Payment::with([
            'invoice.transaction.transactionUser',
            'paymentMethod'
        ])->get();
    }
}
