<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Payment;
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

        //Get convenience fee total
        $totalConvenienceFee = $payments->sum('convenience_fee');   
        
        //Subtract convenience fee from total amount to get net earnings
        $totalAmountEarned = $totalAmount - $totalConvenienceFee;

        $pdf = Pdf::loadView('livewire.admin.reports.payments-report-summary', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'paymentTypeFilter' => $this->paymentTypeFilter,
            'totalPayments' => $totalPayments,
            'totalAmount' => $totalAmount,
            'totalConvenienceFee' => $totalConvenienceFee,
            'totalAmountEarned' => $totalAmountEarned,
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

    // -------------------- EXPORT EXCEL METHOD ------------------------ //
    /**
     * Export to Excel functionality can be implemented 
     * here using a installed library.
     */

    public function exportPaymentExcel(){
        
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

        // --- Totals ---
        $totalPayments = $payments->count();
        $totalAmount = $payments->sum('amount_paid');
        $totalConvenienceFee = $payments->sum('convenience_fee');
        $totalAmountEarned = $totalAmount - $totalConvenienceFee;

        // Define reporting period
        $start_date = $this->startDate;
        $end_date = $this->endDate;

        $filename = 'Payment-Summary-' .
            Carbon::parse($this->startDate)->format('Ymd') . '-' .
            Carbon::parse($this->endDate)->format('Ymd') . '.xlsx';

        return new StreamedResponse(function () use ($payments, $totalPayments, $totalAmount, $totalConvenienceFee, $totalAmountEarned, $start_date, $end_date) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Canopy Header ---
        $sheet->mergeCells('D1:D2');
        $drawing = new Drawing();
        $drawing->setPath(public_path('images/canopy-logo.png'));
        $drawing->setHeight(55);
        $drawing->setCoordinates('D1');
        $drawing->setOffsetX(10);
        $drawing->setWorksheet($sheet);

        // Title
        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Canopy Farm PH');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(18)->getColor()->setRGB('166534');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Address
        $sheet->mergeCells('A4:I4');
        $sheet->setCellValue('A4', '006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getFont()->setSize(11)->getColor()->setRGB('333333');

        // Contact
        $sheet->mergeCells('A5:I5');
        $sheet->setCellValue('A5', '+63 962 447 9893');
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(11)->getColor()->setRGB('333333');

        // Section Title
        $sheet->mergeCells('A6:I6');
        $sheet->setCellValue('A6', 'Payments Summary');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('166534');
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Reporting Period
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
            'Guest Name', 'Transaction Number', 'Invoice Number',
            'Payment Type', 'Payment Date', 'Amount Paid',
            'Mode of Payment', 'Status', 'Convenience Fee'
        ];
        $sheet->fromArray($headers, null, 'A9');

        // Style header
        $headerStyle = $sheet->getStyle('A9:I9');
        $headerStyle->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('166534');
        $sheet->getRowDimension(9)->setRowHeight(25);

        // --- Data Rows ---
        $row = 10;
        foreach ($payments as $p) {
            //Avoid null errors
            $transactionUser = optional(optional(optional($p->invoice)->transaction)->transactionUser);
            $guestName = trim(($transactionUser->first_name ?? '') . ' ' . ($transactionUser->last_name ?? '')) ?: 'N/A';
            $transactionNumber = optional(optional($p->invoice)->transaction)->transaction_number ?? 'N/A';
            $invoiceNumber = $p->invoice->invoice_number ?? 'N/A';
            $paymentType = $p->payment_type ?? 'N/A';
            $paymentDate = $p->payment_date ? Carbon::parse($p->payment_date)->format('F j, Y') : 'N/A';
            $amountPaid = number_format($p->amount_paid ?? 0, 2);
            $modeOfPayment = optional($p->paymentMethod)->name ?? 'N/A';
            $status = ucfirst($p->status ?? 'N/A');
            $convenienceFee = number_format($p->convenience_fee ?? 0, 2);

            $sheet->setCellValue("A{$row}", $guestName);
            $sheet->setCellValue("B{$row}", $transactionNumber);
            $sheet->setCellValue("C{$row}", $invoiceNumber);
            $sheet->setCellValue("D{$row}", $paymentType);
            $sheet->setCellValue("E{$row}", $paymentDate);
            $sheet->setCellValue("F{$row}", $amountPaid);
            $sheet->setCellValue("G{$row}", $modeOfPayment);
            $sheet->setCellValue("H{$row}", $status);
            $sheet->setCellValue("I{$row}", $convenienceFee);

            $row++;
        }

        // --- Summary Section ---
        $row += 1;
        $sheet->setCellValue("A{$row}", 'Summary of Key Metrics');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('166534');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Payments Within Date Range:');
        $sheet->setCellValue("B{$row}", $totalPayments . ' records');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Amount Collected:');
        $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalAmount, 2));
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Convenience Fees:');
        $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalConvenienceFee, 2));
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Amount Earned (Excluding Fees):');
        $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalAmountEarned, 2));

        // Highlight total earned row
        $highlightRange = "A{$row}:B{$row}";
        $sheet->getStyle($highlightRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E6F4EA');
        $sheet->getStyle($highlightRange)->getFont()
            ->setBold(true)
            ->getColor()->setRGB('166534');

        // Auto-size Columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output Excel
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
    }
}   
    
