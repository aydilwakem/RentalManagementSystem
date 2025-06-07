<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class PaymentReports extends Component
{
    public $payments;
    public $transactionUser;
    public $invoice;
    public $transaction;
    public $sortField = 'created_at'; // default sort column
    public $sortDirection = 'desc';   // or 'asc'
    
    public $paymentTypeFilter = '';
    public $paymentStatusFilter = '';

    //To select date ranges
    public $startDate;
    public $endDate;
    
    public $search = '';
    
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


    //  -------------------------------------------- RENDER ----------------------------------------- //
    //Queries all records in payments table by accepting start and end date input and returns those records
    // Also allows the sorting by payment type
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
        ->when($this->startDate && $this->endDate, function ($query) {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();
            $query->whereBetween('payment_date', [$start, $end]);
        })
        ->when($this->paymentTypeFilter, function ($query) {
            $query->where('payment_type', $this->paymentTypeFilter);
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->get();

        return view('livewire.admin.reports.payment-reports');
    }


    // ------------------------------ EXPORT PDF METHOD -------------------------//
    public function exportPaymentSummary(){
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $query = Payment::with([
            'invoice.transaction.transactionUser',
            'paymentMethod'
        ])
        ->when($start && $end, fn($q) => $q->whereBetween('payment_date', [$start, $end]))
        ->when($this->paymentTypeFilter, fn($q) => $q->where('payment_type', $this->paymentTypeFilter))
        ->orderBy('payment_date', 'asc');

        $payments = $query->get();

        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount_paid');

        $pdf = Pdf::loadView('livewire.admin.reports.payments-report-summary', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'paymentTypeFilter' => $this->paymentTypeFilter,
            'totalPayments' => $totalPayments,
            'totalAmount' => $totalAmount,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Payment-Summary-' . Carbon::parse($this->startDate)->format('Ymd') . '-' . Carbon::parse($this->endDate)->format('Ymd') . '.pdf');
    }
}
