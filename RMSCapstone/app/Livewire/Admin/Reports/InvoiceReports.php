<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class InvoiceReports extends Component
{
    public $payments;
    public $invoices;
    public $transactionUser;
    public $invoice;
    public $transaction;
    public $sortField = 'created_at'; // default sort column
    public $sortDirection = 'desc';   // or 'asc'
    public $invoiceTypeFilter = '';
    public $invoiceStatusFilter = '';

    //To select date ranges
    public $startDate;
    public $endDate;

    public $search = '';

    public $reservationTypes;

    // ---------------------------------------------------- RENDER ------------------------------------------------ //
    public function render()
    {
        $search = trim($this->search);

        $this->invoices = Invoice::with([
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
            ->when($this->startDate && $this->endDate, function ($query) {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();
            $query->whereBetween('due_date', [$start, $end]);
        })
        ->when($this->invoiceTypeFilter, function ($query) {
            $query->where('invoice_type', $this->invoiceTypeFilter);
        })
        ->when($this->invoiceStatusFilter, function ($query) {
                $query->where('invoice_status', $this->invoiceStatusFilter);
            })
        ->orderBy($this->sortField, $this->sortDirection)
        ->get();
            
            
        return view('livewire.admin.reports.invoice-reports');
    }

    // ------------------------------------------------------------------- SORT BY ----------------------------------------//

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    //--------------------------------------------------------------- EXPORT PDF ----------------------------------------- //
    public function exportInvoiceSummary(){
    
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $query = Invoice::with([
            'transaction.transactionUser',
            'payments.paymentMethod'
        ])
        ->when($start && $end, fn ($q) => $q->whereBetween('due_date', [$start, $end]))
        ->when($this->invoiceTypeFilter, fn ($q) => $q->where('invoice_type', $this->invoiceTypeFilter))
        ->when($this->invoiceStatusFilter, fn ($q) => $q->where('invoice_status', $this->invoiceStatusFilter))
        ->orderBy('due_date', 'asc');

        $invoices = $query->get();

        $totalInvoices = $invoices->count();
        $totalAmountPaid = $invoices->sum('amount_paid');
        $totalBalanceDue = $invoices->sum('balance_due');

        $pdf = Pdf::loadView('livewire.admin.reports.invoices-report-summary', [
            'invoices' => $invoices,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'invoiceTypeFilter' => $this->invoiceTypeFilter,
            'invoiceStatusFilter' => $this->invoiceStatusFilter,
            'totalInvoices' => $totalInvoices,
            'totalAmountPaid' => $totalAmountPaid,
            'totalBalanceDue' => $totalBalanceDue,
        ])->setPaper('a4', 'portrait');

        $fileName = 'Invoice-Summary-' .
            ($this->startDate ? Carbon::parse($this->startDate)->format('Ymd') : 'Start') . '-' .
            ($this->endDate ? Carbon::parse($this->endDate)->format('Ymd') : 'End') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }
}
