<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DaytourReports extends Component
{
    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 4; //Filter daytour transactions only
    public $sortBy = 'updated_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $perPage = 10;

    //Date ranges
    public $start_date;
    public $end_date;

    //Filters
    public $filteredTransactions = [];
    // public $hallFilter = '';
    // public $halls = []; 
    public $daytourStatusFilter = '';
    public $filterApplied = false;

    // ---------------- Mount Method ---------------- //
    public function mount (){
        // Initializes session variable if not already set
        if (!session()->has('fake_ids_daytour-reports')) {
            session(['fake_ids_daytour-reports' => []]);
        }

        // Set default date range to the current month
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->copy()->startOfMonth()->format('Y-m-d');
        $this->end_date = $now->copy()->endOfMonth()->format('Y-m-d');

    }

    // ----------------- Filter Method ----------------- //
    public function applyDaytourFilter(){
        $query = Transaction::query()
        ->select('trn_transactions.*')
        ->with(['transactionUser', 'guestDetails', 'dayTour', 'dayTourRate', 'invoice.payments'])
        ->where('trn_transactions.reservation_type_id', $this->reservation_type_id); // 4 for daytour

        //filter by date range and status: 
         if ($this->start_date && $this->end_date) {
        $query->whereBetween('trn_transactions.start_datetime', [
            Carbon::parse($this->start_date)->startOfDay(),
            Carbon::parse($this->end_date)->endOfDay(),
        ]);
        } elseif ($this->start_date) {
            $query->whereDate('trn_transactions.start_datetime', '>=', Carbon::parse($this->start_date));
        } elseif ($this->end_date) {
            $query->whereDate('trn_transactions.start_datetime', '<=', Carbon::parse($this->end_date));
        }

        // Filter by transaction status (if selected)
        if (!empty($this->daytourStatusFilter)) {
            $query->where('trn_transactions.transaction_status', $this->daytourStatusFilter);
        }

        // Fetch results with sorting and pagination
        $this->filteredTransactions = $query
            ->orderBy($this->sortBy, $this->sortDir)
            ->get(); 
            // ->paginate($this->perPage);

        // Mark that a filter is active
        $this->filterApplied = true;
    }

    public function exportDaytourSummary(){
        $query = Transaction::query()
        ->select('trn_transactions.*')
        ->with(['transactionUser', 'guestDetails', 'dayTour', 'dayTourRate', 'invoice.payments'])
        ->where('trn_transactions.reservation_type_id', $this->reservation_type_id); // 4 = Daytour

        //Filter the dates
        if ($this->start_date && $this->end_date) {
        $query->whereBetween('trn_transactions.start_datetime', [
            Carbon::parse($this->start_date)->startOfDay(),
            Carbon::parse($this->end_date)->endOfDay(),
        ]);
        } elseif ($this->start_date) {
            $query->whereDate('trn_transactions.start_datetime', '>=', Carbon::parse($this->start_date));
        } elseif ($this->end_date) {
            $query->whereDate('trn_transactions.start_datetime', '<=', Carbon::parse($this->end_date));
        }

        //Get the status
         if (!empty($this->daytourStatusFilter)) {
        $query->where('trn_transactions.transaction_status', $this->daytourStatusFilter);
    }

    // Fetch transactions
    $transactions = $query
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();

    // Summary calculations
        $totalDaytours = $transactions->count();
        $totalGuests = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('sub_total');

        $pdf = Pdf::loadView('livewire.admin.reports.daytour-reports-summary', [
            'transactions' => $transactions,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'daytourStatusFilter' => $this->daytourStatusFilter,
            'totalDaytours' => $totalDaytours,
            'totalGuests' => $totalGuests,
            'totalAmountEarned' => $totalAmountEarned,
        ]);

        // Downloadable filename format
        $filename = 'Daytour-Summary-' .
            Carbon::parse($this->start_date)->format('Ymd') . '-' .
            Carbon::parse($this->end_date)->format('Ymd') . '.pdf';

        // Stream download response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }

    // ------------------ Export to Excel Method ------------------ //
    /**
     * Exports day tour report to Excel.
     */
    public function exportDaytourExcel(){
        $query = Transaction::query()
        ->select('trn_transactions.*')
        ->with(['transactionUser', 'guestDetails', 'dayTour', 'dayTourRate', 'invoice.payments'])
        ->where('trn_transactions.reservation_type_id', $this->reservation_type_id); // 4 for daytour

    // --- Apply Filters ---
    if ($this->start_date && $this->end_date) {
        $query->whereBetween('trn_transactions.start_datetime', [
            Carbon::parse($this->start_date)->startOfDay(),
            Carbon::parse($this->end_date)->endOfDay(),
        ]);
    } elseif ($this->start_date) {
        $query->whereDate('trn_transactions.start_datetime', '>=', Carbon::parse($this->start_date));
    } elseif ($this->end_date) {
        $query->whereDate('trn_transactions.start_datetime', '<=', Carbon::parse($this->end_date));
    }

    // --- Status Filter ---
    if (!empty($this->daytourStatusFilter)) {
        $query->where('trn_transactions.transaction_status', $this->daytourStatusFilter);
    }

    $transactions = $query
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();

    //Calculations
    $totalDaytours = $transactions->count();
    $totalGuests = $transactions->sum('pax');
    $totalAmountEarned = $transactions->sum('sub_total');

    //Define dates
    $start_date = $this->start_date;
    $end_date = $this->end_date;

    //File name
    $filename = 'Day Tour-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.xlsx';

    return new StreamedResponse(function() use ($transactions, $totalDaytours, $totalGuests, $totalAmountEarned, $start_date, $end_date) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //Canopy Header - Logo displayed above
        //Merge cells for logo: 
        $sheet->mergeCells('C1:C2');
        $drawing = new Drawing();
        $drawing->setPath(public_path('images/canopy-logo.png')); 
        $drawing->setHeight(55); //Logo size
        $drawing->setCoordinates('C1'); 
        $drawing->setOffsetX(20); //Adjust row
        $drawing->setOffsetX(10); //Adjust down
        $drawing->setWorksheet($sheet);

        // Title
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Canopy Farm PH');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(18)->getColor()->setRGB('166534');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Address
        $sheet->mergeCells('A4:J4');
        $sheet->setCellValue('A4', '006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getFont()->setSize(11)->getColor()->setRGB('333333');

        // Contact
        $sheet->mergeCells('A5:J5');
        $sheet->setCellValue('A5', '+63 962 447 9893');
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(11)->getColor()->setRGB('333333');

        // Section Title
        $sheet->mergeCells('A6:J6');
        $sheet->setCellValue('A6', 'Reservations Summary');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('166534');
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //Reporting Period
        $sheet->mergeCells('A7:J7');

        if ($start_date && $end_date) {
            $reportingPeriod = 'Reporting Period: ' .
                \Carbon\Carbon::parse($start_date)->format('F d, Y') . ' – ' .
                \Carbon\Carbon::parse($end_date)->format('F d, Y');
        } else {
            $reportingPeriod = 'Reporting Period: All Records';
        }

        $sheet->setCellValue('A7', $reportingPeriod);
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('333333');
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // --- Header Row ---
        $headers = ['Transaction ID','Guest Name','Tour Package','Tour Date','Pax','Amount','Status'];
        $sheet->fromArray($headers, null, 'A9');

        // --- Style Header ---
        $headerStyle = $sheet->getStyle('A9:G9'); //Move cells down
        $headerStyle->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('166534'); // Dark green
        $sheet->getRowDimension(1)->setRowHeight(25);

        // --- Transactions ---
        $row = 10; //orginally 8 Rows
        foreach ($transactions as $t) {
            //$halls = $t->properties->pluck('name_number')->implode(', ');
            $userName = trim(optional($t->transactionUser)?->first_name . ' ' . optional($t->transactionUser)?->last_name) ?: 'N/A';

            $sheet->setCellValue("A{$row}", $t->transaction_number);
            $sheet->setCellValue("B{$row}", $userName);
            $sheet->setCellValue("C{$row}", optional($t->dayTour)->name);
            $sheet->setCellValue("D{$row}", Carbon::parse($t->start_datetime)->format('F j, Y'));
            $sheet->setCellValue("E{$row}", $t->pax);
            $sheet->setCellValue("F{$row}", number_format($t->sub_total, 2));
            $sheet->setCellValue("G{$row}", ucfirst($t->transaction_status));

            $row++;
        }

        // Summary
        $row += 1; // Blank line
        $sheet->setCellValue("A{$row}", 'Summary of Key Metrics');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('166534');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Day Tours Within Date Range:');
        $sheet->setCellValue("B{$row}", $totalDaytours . ' reservations');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Guests:');
        $sheet->setCellValue("B{$row}", $totalGuests . ' guests');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Amount Earned:');
        $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalAmountEarned, 2));
        
        // Apply light green background highlight to both A and B cells
        $highlightRange = "A{$row}:B{$row}";
        $sheet->getStyle($highlightRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E6F4EA');

        // Make the text bold and dark green for emphasis
        $sheet->getStyle($highlightRange)->getFont()
            ->setBold(true)
            ->getColor()->setRGB('166534');

        // --- Auto-size columns ---
        foreach (range('A', 'J') as $col) {
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


    public function render()
    {
        return view('livewire.admin.reports.daytour-reports', [
            'filteredTransactions' => $this->filterApplied
                ? $this->filteredTransactions
                : collect(), // Empty before applying filter
        ]);
    
    }
}
