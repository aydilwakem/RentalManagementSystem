<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class PaymentReports extends Component
{
    // ------------------ VARIABLE DECLARATIONS ------------------------- //
    public $payments;
    public $transactionUser;
    public $invoice;
    public $transaction;
    public $sortField = 'created_at'; // default sort column
    public $sortDirection = 'desc';   // or 'asc'

    // --------------------------- FILTER ------------------------------ //
    public $paymentTypeFilter = '';
    public $paymentStatusFilter = '';
    public $filteredPayments = [];
    public $filterApplied = false;

    //----------------------------- DATE RANGE ---------------------- //
    public $startDate;
    public $endDate;

    public $search = '';

    // ---------------------------- MOUNT ------------------------ //
    public function mount()
    {
        $this->payments = Payment::with([
            'invoice.transaction.transactionUser',
            'paymentMethod'
        ])->get();


        //Default date range current month
        $now = Carbon::now('Asia/Manila');
        $this->startDate = $now->startOfMonth()->format('Y-m-d');
        $this->endDate = $now->endOfMonth()->format('Y-m-d');
    }

    // ------------------------- SORT ----------------------------- //
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

    //----------------------- APPLY FILTER BTN ---------------- //
    public function applyPaymentFilter(){
        $search = trim($this->search);

    $this->filteredPayments = Payment::with([
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

        $this->filterApplied = true;
    }

    // --------------------- EXPORT CSV METHOD ------------------------ //
     public function exportPaymentCsv(){
        //Query the table
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

        //Calculations
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount_paid');

        //CSV File Definitions
        $fileName = 'Payment-Summary-' .
        ($this->startDate ? Carbon::parse($this->startDate)->format('Ymd') : 'Start') . '-' .
        ($this->endDate ? Carbon::parse($this->endDate)->format('Ymd') : 'End') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

       return response()->stream(function () use ($payments, $totalPayments, $totalAmount) {
        $handle = fopen('php://output', 'w');

        // Header row
        fputcsv($handle, [
            'No.',
            'Guest Name',
            'Transaction No.',
            'Invoice Number',
            'Payment Type',
            'Payment Date',
            'Amount Paid',
            'Mode of Payment',
            'Status',
        ]);

        $i = 1;
        foreach ($payments as $payment) {
            
            $guest = optional(optional(optional($payment->invoice)->transaction)->transactionUser);
            
            $guestName = trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? 'Guest Detail Has Been Deleted'));
            
            $transaction = optional(optional($payment->invoice)->transaction);
            
            $invoice = optional($payment->invoice);
            
            $status = $payment->payment_status ?? 'N/A'; // adjust this if you use a different status field

            fputcsv($handle, [
                str_pad($i++, 3, '0', STR_PAD_LEFT),
                $guestName ?: 'N/A',
                $transaction->transaction_number ?? 'N/A',
                $invoice->invoice_number ?? 'N/A',
                $payment->payment_type, 
                optional($payment->payment_date)->format('M j, Y') ?? 'N/A',
                number_format($payment->amount_paid, 2),
                $payment->mode_of_payment ?? $payment->paymentMethod->mode_ofpayment_name,
                ucfirst($status),
            ]);
        }

        // Summary
        fputcsv($handle, []);
        fputcsv($handle, ['Summary of Key Metrics']);
        fputcsv($handle, ['Total Payments', $totalPayments]);
        fputcsv($handle, ['Total Amount Paid', '₱' . number_format($totalAmount, 2)]);

        fclose($handle);
    }, 200, $headers);
    }

}   
    
