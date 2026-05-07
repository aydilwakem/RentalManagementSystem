<?php

namespace App\Livewire\Admin\Reservations\Invoice;

use Livewire\Component;
use App\Models\ReservationType;
use App\Models\Payment;
use App\Models\Invoice;
use Livewire\WithPagination;

class InvoiceList extends Component
{

    use WithPagination;
    public $payments;
    //public $invoices;
    public $transactionUser;
    public $invoice;
    public $transaction;
    public $sortField = 'created_at'; // default sort column
    public $sortDirection = 'desc';   // or 'asc'
    public $perPage = 10;

    public $invoiceTypeFilter = '';
    public $invoiceStatusFilter = '';

    public $search = '';

    public $reservationTypes;

    public function render()
    {
        $search = trim($this->search);

        $invoices = Invoice::with([
            'transaction.transactionUser',
            'payments.paymentMethod'
        ])
            ->when($search, function ($query) use ($search) {
                $names = explode(' ', $search);

                $query->whereHas('transaction.transactionUser', function ($q) use ($names) {
                    $q->where(function ($subQuery) use ($names) {
                        if (count($names) === 1) {
                            $subQuery->where('first_name', 'like', '%' . $names[0] . '%')
                                ->orWhere('last_name', 'like', '%' . $names[0] . '%');
                        } elseif (count($names) >= 2) {
                            $firstName = $names[0];
                            $lastName = $names[count($names) - 1];
                            $subQuery->where('first_name', 'like', '%' . $firstName . '%')
                                ->where('last_name', 'like', '%' . $lastName . '%');
                        }
                    });
                });
            })
            ->when($this->invoiceStatusFilter, function ($query) {
                $query->where('invoice_status', $this->invoiceStatusFilter);
            })
            ->when($this->invoiceTypeFilter, function ($query) {
                $query->where('invoice_type', $this->invoiceTypeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage); 

        return view('livewire.admin.reservations.invoice.invoice-list', [
            'invoices' => $invoices, 
        ]);
    }


    public function mount()
    {
        $invoices = Invoice::with([
            'transaction.transactionUser',
            'payments.paymentMethod'
        ])->get();

        $this->reservationTypes = ReservationType::all();
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
