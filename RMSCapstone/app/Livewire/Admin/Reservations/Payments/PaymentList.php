<?php

namespace App\Livewire\Admin\Reservations\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\PropertyType;

class PaymentList extends Component
{

    public $payments;
    public $transactionUser;
    public $invoice;
    public $transaction;
    public $sortField = 'created_at'; // default sort column
    public $sortDirection = 'desc';   // or 'asc'
    public $paymentTypeFilter = '';
    public $paymentStatusFilter = '';

    public $search = '';

    public function render()
    {
        $search = trim($this->search);

        $this->payments = Payment::with([
            'invoice.transaction.transactionUser',
            'paymentMethod'
        ])
            ->when($search, function ($query) use ($search) {
                $names = explode(' ', $search);

                $query->whereHas('invoice.transaction.transactionUser', function ($q) use ($names) {
                    $q->where(function ($subQuery) use ($names) {
                        if (count($names) === 1) {
                            // If only one word, search first_name or last_name
                            $subQuery->where('first_name', 'like', '%' . $names[0] . '%')
                                ->orWhere('last_name', 'like', '%' . $names[0] . '%');
                        } elseif (count($names) >= 2) {
                            // If more than one word, assume first word is first_name, last word is last_name
                            $firstName = $names[0];
                            $lastName = $names[count($names) - 1];
                            $subQuery->where('first_name', 'like', '%' . $firstName . '%')
                                ->where('last_name', 'like', '%' . $lastName . '%');
                        }
                    });
                });
            })
            ->when($this->paymentTypeFilter, function ($query) {
                $query->where('payment_type', $this->paymentTypeFilter);
            })
            ->when($this->paymentStatusFilter, function ($query) {
                $query->where('payment_status', $this->paymentStatusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        return view('livewire.admin.reservations.payments.payment-list');
    }


    public function mount()
    {
        $this->payments = Payment::with([
            'invoice.transaction.transactionUser',
            'paymentMethod'
        ])->get();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
