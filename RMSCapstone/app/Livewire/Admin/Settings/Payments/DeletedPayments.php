<?php

namespace App\Livewire\Admin\Settings\Payments;

use App\Models\PaymentMethod;
use Livewire\Component;

class DeletedPayments extends Component
{

    public $deletedPayments;

    public function mount()
    {
        $this->fetchDeletedPayments();
    }

    public function fetchDeletedPayments()
    {
        $this->deletedPayments = PaymentMethod::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restorePayment($paymentId)
    {
        $payment = PaymentMethod::withTrashed()->find($paymentId);
        if ($payment) {
            $payment->restore();
            session()->flash('message', 'Payment method restored successfully.');
            $this->fetchDeletedPayments();
        }
    }

    public function deletePaymentForever($paymentId)
    {
        $payment = PaymentMethod::withTrashed()->find($paymentId);
        if ($payment) {
            $payment->forceDelete();
            session()->flash('message', 'Payment method permanently deleted.');
            $this->fetchDeletedPayments();
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.payments.deleted-payments');
    }
}
