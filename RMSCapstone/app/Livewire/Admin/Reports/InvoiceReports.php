<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class InvoiceReports extends Component
{
    // ---------------- VARIABLE DECLARATIONS --------------- //
    public $payments;
    public $invoices;
    public $transactionUser;
    public $invoice;
    public $transaction;
    public $sortField = 'created_at'; // default sort column
    public $sortDirection = 'desc';   // or 'asc'
    public $search = '';
    public $reservationTypes;

    //---------------------- DATE RANGE INPUTS -------------------- //
    public $startDate;
    public $endDate;

    // --------------------- FILTERS --------------------------- //
    public $filteredInvoices = [];
    public $filterApplied = false;
    public $invoiceTypeFilter = '';
    public $invoiceStatusFilter = '';

    
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

    //--------------------------- APPLY FILTER METHOD --------------------------- //
    public function applyInvoiceFilter(){
        $search = trim($this->search);

        $this->filteredInvoices  = Invoice::with([
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

        $this->filterApplied = true;
    }

    //--------------------------------------------------------------- MOUNT ----------------------------------------- //
    public function mount()
    {
        //Default date range current month
        $now = Carbon::now('Asia/Manila');
        $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
        $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
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

    // ------------------------------- EXPORT CSV METHOD --------------------------------------- //
    public function exportInvoiceCsv(){
        //Parse the input daes
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        //Join tables in query
        $query = Invoice::with([
            'transaction.transactionUser',
            'payments.paymentMethod'
        ])
            ->when($start && $end, fn ($q) => $q->whereBetween('due_date', [$start, $end]))
            ->when($this->invoiceTypeFilter, fn ($q) => $q->where('invoice_type', $this->invoiceTypeFilter))
            ->when($this->invoiceStatusFilter, fn ($q) => $q->where('invoice_status', $this->invoiceStatusFilter))
            ->orderBy('due_date', 'asc');

    $invoices = $query->get();

    //Calculations
    $totalInvoices = $invoices->count();
    $totalAmountPaid = $invoices->sum('amount_paid');
    $totalBalanceDue = $invoices->sum('balance_due');

    $fileName = 'Invoice-Summary-' .
        ($this->startDate ? Carbon::parse($this->startDate)->format('Ymd') : 'Start') . '-' .
        ($this->endDate ? Carbon::parse($this->endDate)->format('Ymd') : 'End') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    return response()->stream(function () use ($invoices, 
    $totalInvoices, 
    $totalAmountPaid, 
    $totalBalanceDue) {
        $handle = fopen('php://output', 'w');

        // Header row
        fputcsv($handle, [
            'No.',
            'Invoice Number', 
            'Guest Name',
            'Transaction No.',
            'Billing Date',
            'Payment Date',
            'Amount Paid',
            'Balance Due',
            'Invoice Type',
            'Status',
        ]);

        $i = 1;
        foreach ($invoices as $invoice) {
            $guest = optional(optional($invoice->transaction)->transactionUser);
            $guestName = trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? ''));

            fputcsv($handle, [
                'INV-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                $invoice->invoice_number ?? 'N/A',
                $guestName ?: 'N/A',
                $invoice->transaction->transaction_number ?? 'N/A',
                optional($invoice->created_at)->format('M j, Y') ?? 'N/A',
                optional($invoice->due_date)->format('M j, Y') ?? 'N/A',
                number_format($invoice->sub_total, 2),
                number_format($invoice->balance_due, 2),
                ucfirst($invoice->invoice_type ?? 'N/A'),
                ucfirst($invoice->invoice_status ?? 'Unknown'),
            ]);
        }

        //Row space
        fputcsv($handle, []);
        fputcsv($handle, ['Summary of Key Metrics']);
        fputcsv($handle, ['Total Invoices', $totalInvoices]);
        fputcsv($handle, ['Total Amount Paid', '₱' . number_format($totalAmountPaid, 2)]);
        fputcsv($handle, ['Total Balance Due', '₱' . number_format($totalBalanceDue, 2)]);

        fclose($handle);
        }, 200, $headers);

    }


}
