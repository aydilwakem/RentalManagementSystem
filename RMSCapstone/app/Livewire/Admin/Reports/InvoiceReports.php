<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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

    //----------------------------------- EXPORT PDF ------------------------------ //
    public function exportInvoiceSummary(){
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        //lazy load guest and payments
        $query = Invoice::with([
            'transaction.transactionUser',
            'payments.paymentMethod'
        ])
        ->when($start && $end, fn ($q) => $q->whereBetween('due_date', [$start, $end]))
        ->when($this->invoiceTypeFilter, fn ($q) => $q->where('invoice_type', $this->invoiceTypeFilter))
        ->when(
            $this->invoiceStatusFilter && $this->invoiceStatusFilter !== '', //if all
            fn ($q) => $q->where('invoice_status', $this->invoiceStatusFilter)
        )
        ->orderBy('due_date', 'asc');

        $invoices = $query->get();

        // Totals
        $totalInvoices = $invoices->count();
        $totalAmountPaid = $invoices->sum('amount_paid');
        $totalBalanceDue = $invoices->sum('balance_due');

        // If invoiceStatusFilter = 'All', count each status category
        $statusCounts = [
            'pending' => 0,
            'completed' => 0,
            'failed' => 0,
            'overdue' => 0,
        ];

        if ($this->invoiceStatusFilter === '') {
            $statusCounts['pending']   = $invoices->where('invoice_status', 'pending')->count();
            $statusCounts['completed'] = $invoices->where('invoice_status', 'completed')->count();
            $statusCounts['failed']    = $invoices->where('invoice_status', 'failed')->count();
            $statusCounts['overdue']   = $invoices->where('invoice_status', 'overdue')->count();
        }

        $pdf = Pdf::loadView('livewire.admin.reports.invoices-report-summary', [
            'invoices' => $invoices,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'invoiceTypeFilter' => $this->invoiceTypeFilter,
            'invoiceStatusFilter' => $this->invoiceStatusFilter,
            'totalInvoices' => $totalInvoices,
            'totalAmountPaid' => $totalAmountPaid,
            'totalBalanceDue' => $totalBalanceDue,
            'statusCounts' => $statusCounts, //pass counts to Blade
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

    // ------------------------- EXPORT EXCEL METHOD ----------------//
    /**
     * Export invoices to Excel.
     */

    public function exportInvoiceExcel(){
        //Define dates and lazy load
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $query = Invoice::with([
            'transaction.transactionUser',
            'payments.paymentMethod'
        ])
        ->when($start && $end, fn ($q) => $q->whereBetween('due_date', [$start, $end]))
        ->when($this->invoiceTypeFilter, fn ($q) => $q->where('invoice_type', $this->invoiceTypeFilter))
        ->when(
            $this->invoiceStatusFilter && $this->invoiceStatusFilter !== '',
            fn ($q) => $q->where('invoice_status', $this->invoiceStatusFilter)
        )
        ->orderBy('due_date', 'asc');

        $invoices = $query->get(); //fetch queries

        //Calculations
          $totalInvoices = $invoices->count();
        $totalAmountPaid = $invoices->sum('amount_paid');
        $totalBalanceDue = $invoices->sum('balance_due');

        // Status counts (only if All)
        $statusCounts = [
            'pending' => 0,
            'completed' => 0,
            'failed' => 0,
            'overdue' => 0,
        ];

         if ($this->invoiceStatusFilter === '') {
        $statusCounts['pending']   = $invoices->where('invoice_status', 'pending')->count();
        $statusCounts['completed'] = $invoices->where('invoice_status', 'completed')->count();
        $statusCounts['failed']    = $invoices->where('invoice_status', 'failed')->count();
        $statusCounts['overdue']   = $invoices->where('invoice_status', 'overdue')->count();
    }

        $start_date = $this->startDate;
        $end_date = $this->endDate;

        $filename = 'Invoice-Summary-' .
            ($this->startDate ? Carbon::parse($this->startDate)->format('Ymd') : 'Start') . '-' .
            ($this->endDate ? Carbon::parse($this->endDate)->format('Ymd') : 'End') . '.xlsx';

        return new StreamedResponse(function () use ($invoices, $totalInvoices, $totalAmountPaid, $totalBalanceDue, $statusCounts, $start_date, $end_date) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Header Section ---
        $sheet->mergeCells('D1:D2');
        $drawing = new Drawing();
        $drawing->setPath(public_path('images/canopy-logo.png'));
        $drawing->setHeight(55);
        $drawing->setCoordinates('D1');
        $drawing->setOffsetX(10);
        $drawing->setWorksheet($sheet);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Canopy Farm PH');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(18)->getColor()->setRGB('166534');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A4:I4');
        $sheet->setCellValue('A4', '006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getFont()->setSize(11)->getColor()->setRGB('333333');

        $sheet->mergeCells('A5:I5');
        $sheet->setCellValue('A5', '+63 962 447 9893');
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(11)->getColor()->setRGB('333333');

        $sheet->mergeCells('A6:I6');
        $sheet->setCellValue('A6', 'Invoice Summary');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('166534');
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A7:I7');
        if ($start_date && $end_date) {
            $reportingPeriod = 'Reporting Period: ' .
                Carbon::parse($start_date)->format('F d, Y') . ' – ' .
                Carbon::parse($end_date)->format('F d, Y');
        } else {
            $reportingPeriod = 'Reporting Period: All Records';
        }
        $sheet->setCellValue('A7', $reportingPeriod);
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('333333');
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // --- Header Row ---
        $headers = [
            'Invoice Number', 'Guest Name', 'Transaction Number', 'Invoice Type',
            'Amount Paid', 'Balance Due', 'Billing Date' ,'Due Date', 'Status'
        ];
        $sheet->fromArray($headers, null, 'A9');

        $headerStyle = $sheet->getStyle('A9:I9');
        $headerStyle->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('166534');
        $sheet->getRowDimension(9)->setRowHeight(25);

        // --- Data Rows ---
        $row = 10;
        foreach ($invoices as $inv) {
            $user = optional(optional($inv->transaction)->transactionUser);
            $guestName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A';
            $transactionNumber = optional($inv->transaction)->transaction_number ?? 'N/A';
            $invoiceType = ucfirst($inv->invoice_type ?? 'N/A');
            $amountPaid = number_format($inv->amount_paid ?? 0, 2);
            $balanceDue = number_format($inv->balance_due ?? 0, 2);
            $billDate = $inv->created_at ? Carbon::parse($inv->created_at)->format('F j, Y') : 'N/A';
            $dueDate = $inv->due_date ? Carbon::parse($inv->due_date)->format('F j, Y') : 'N/A';
            $status = ucfirst($inv->invoice_status ?? 'N/A');

            $sheet->setCellValue("A{$row}", $inv->invoice_number);
            $sheet->setCellValue("B{$row}", $guestName);
            $sheet->setCellValue("C{$row}", $transactionNumber);
            $sheet->setCellValue("D{$row}", $invoiceType);
            $sheet->setCellValue("E{$row}", $amountPaid);
            $sheet->setCellValue("F{$row}", $balanceDue);
            $sheet->setCellValue("G{$row}", $billDate);
            $sheet->setCellValue("H{$row}", $dueDate);
            $sheet->setCellValue("I{$row}", ucfirst($status));
            

            $row++;
        }

        // --- Summary Section ---
        $row += 1;
        $sheet->setCellValue("A{$row}", 'Summary of Key Metrics');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('166534');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Invoices:');
        $sheet->setCellValue("B{$row}", $totalInvoices . ' records');
        $row++;

        // $sheet->setCellValue("A{$row}", 'Total Amount Paid:');
        // $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalAmountPaid, 2));
        // $row++;

        // $sheet->setCellValue("A{$row}", 'Total Balance Due:');
        // $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalBalanceDue, 2));
        // $row++;

        // Show status counts if All
        if ($statusCounts['pending'] || $statusCounts['completed'] || $statusCounts['failed'] || $statusCounts['overdue']) {
            $row++;
            $sheet->setCellValue("A{$row}", 'Status Breakdown');
            $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('166534');
            $row++;

            $sheet->setCellValue("A{$row}", 'Pending Invoices:');
            $sheet->setCellValue("B{$row}", $statusCounts['pending'] . ' records');
            $row++;

            $sheet->setCellValue("A{$row}", 'Completed Invoices:');
            $sheet->setCellValue("B{$row}", $statusCounts['completed'] . ' records');
            $row++;

            $sheet->setCellValue("A{$row}", 'Failed Invoices:');
            $sheet->setCellValue("B{$row}", $statusCounts['failed'] . ' records');
            $row++;

            $sheet->setCellValue("A{$row}", 'Overdue Invoices:');
            $sheet->setCellValue("B{$row}", $statusCounts['overdue'] . ' records');
        }

        // --- Auto-size Columns ---
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Output ---
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);

    }

}
