<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Property;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class LeaseReports extends Component
{
    // ---------------------- VARIABLE DECLARATIONS ---------------- //
    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 1; //Filter lease transactions only
    public $sortBy = 'updated_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $perPage = 10;

    // ------------------------- FOR DATE RANGES INPUT --------------- //
    public $start_date;
    public $end_date;

    //-------------------------- FILTERS ------------------------------- //
    public $filteredTransactions = [];
    public $propertyFilter = '';
    public $properties = []; 
    public $propertyStatusFilter = '';
    public $filterApplied = false;

    // --------------------------- MOUNT -------------------------------- //
    public function mount(){
         // Initializes session variable if not already set
        if (!session()->has('fake_ids_lease-reports')) {
            session(['fake_ids_lease-reports' => []]);
        }

        //For default date range
        $now = Carbon::now('Asia/Manila');

        // Get the current quarter (bc minimum lease is 3 months)
        $quarter = $now->quarter;
        $startOfQuarter = Carbon::create($now->year, ($quarter - 1) * 3 + 1, 1)->startOfMonth();
        $endOfQuarter = Carbon::create($now->year, $quarter * 3, 1)->endOfMonth();

        //Start date is the first day of the quarter
        //End date is the last day of the quarter
        $this->start_date = $startOfQuarter->format('Y-m-d');
        $this->end_date = $endOfQuarter->format('Y-m-d');

        //Fetch all properties
        $this->properties = Property::where('property_type_id', 2)->get(); //fetch all houses

    }

    // ------------------------------------ FILTER BUTTON --------------------------------- //
    public function applyLeaseFilter()
    {
        //Query tables with FKs
       $query = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 1); //Fetch all leases

       if ($this->start_date) {
            $query->whereDate('start_datetime', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('start_datetime', '<=', $this->end_date);
        }

        if ($this->propertyFilter) {
            $query->where('transaction_properties.property_id', $this->propertyFilter);
        }

        $query->when($this->propertyStatusFilter, function ($query) {
            $query->where('transaction_status', $this->propertyStatusFilter);
        });

        //use variable for filtering transactions
        $this->filteredTransactions = $query
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $this->filterApplied = true;
    }


    //----------------------------------- EXPORT PDF BUTTON ---------------------------- - //
    public function exportLeaseSummary(){
        
        //Query all tables with join
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 1) //1 for lease transactions
            ->when($this->start_date, function ($query) {
                $start = Carbon::parse($this->start_date)->startOfDay();
                $query->where('start_datetime', '>=', $start);
            })
            ->when($this->end_date, function ($query) {
                $end = Carbon::parse($this->end_date)->endOfDay();
                $query->where('start_datetime', '<=', $end);
            })
            ->when($this->propertyFilter, function ($query) { //Property filter
                $query->where('transaction_properties.property_id', $this->propertyFilter);
            })
            ->when($this->propertyStatusFilter, function ($query) { //Status filter
                $query->where('transaction_status', $this->propertyStatusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

            //count leases
            $totalLeases = $transactions->count();

            //Calculate average stay in months
            if ($totalLeases > 0) {
            $totalMonths = $transactions->sum(function($transaction) {
                $start = Carbon::parse($transaction->start_datetime);
                $end = Carbon::parse($transaction->end_datetime);
                return $start->diffInMonths($end); // whole months only
            });

            $averageLength = $totalMonths / $totalLeases;
        } else {
            $averageLength = 0;
        }

        //Sum of tenants and amount
            $totalTenants = $transactions->sum('pax');
            $totalAmountEarned = $transactions->sum('total_amount');

        //----------------- Most Booked Hall within Date Range -------------------- //
        $mostBookedProperty = null;

            if (!$this->propertyFilter && $transactions->isNotEmpty()) {
            $propertyCounts = [];

            foreach ($transactions as $transaction) {
                foreach ($transaction->properties as $property) {
                    $propertyName = $property->name_number;

                    if (!isset($propertyCounts[$propertyName])) {
                        $propertyCounts[$propertyName] = 0;
                    }

                    $propertyCounts[$propertyName]++;
                }
            }

            if (!empty($propertyCounts)) {
            arsort($propertyCounts); // Sort descending by count
            $topProperty = array_key_first($propertyCounts);
            $count = $propertyCounts[$topProperty];

            $mostBookedProperty = $topProperty . ' (' . $count . ' leases)';
            }

            //For checking
            Log::info("Most Booked Property Count (from transactions):", [
            'propertyCounts' => $propertyCounts,
            'mostBookedProperty' => $mostBookedProperty,
            ]);
        }


        // ------------------------ PDF VARIABLES ---------------------------- //
        $pdf = Pdf::loadView('livewire.admin.reports.leases-report-summary', [
            'transactions' => $transactions,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'averageLength' => round($averageLength, 2),
            'totalLeases' => $totalLeases,
            'totalTenants' => $totalTenants,
            'totalAmountEarned' => $totalAmountEarned,
            'properties' => $this->properties,
            'propertyFilter' => $this->propertyFilter, //Added variables to pdf 
            'propertyStatusFilter' => $this->propertyStatusFilter,
            'mostBookedProperty' => $mostBookedProperty
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Lease-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.pdf');
    }


    // ---------------------------------- EXPORT CSV METHOD --------------------------------- //
    public function exportLeaseCsv(){
        //Query all tables and fetch using get
        $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 1) //Lease ID
        ->when($this->start_date, function ($query) {
            $start = Carbon::parse($this->start_date)->startOfDay();
            $query->where('start_datetime', '>=', $start);
        })
        ->when($this->end_date, function ($query) {
            $end = Carbon::parse($this->end_date)->endOfDay();
            $query->where('start_datetime', '<=', $end);
        })
        ->when($this->propertyFilter, function ($query) {
            $query->where('transaction_properties.property_id', $this->propertyFilter);
        })
        ->when($this->propertyStatusFilter, function ($query) {
            $query->where('transaction_status', $this->propertyStatusFilter);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();


        //Calculations
    $totalLeases = $transactions->count();
    $totalTenants = $transactions->sum('pax');
    $totalAmountEarned = $transactions->sum('total_amount');

   //Calculate average stay in months
            if ($totalLeases > 0) {
            $totalMonths = $transactions->sum(function($transaction) {
                $start = Carbon::parse($transaction->start_datetime);
                $end = Carbon::parse($transaction->end_datetime);
                return $start->diffInMonths($end); // whole months only
            });

            $averageLength = $totalMonths / $totalLeases;
        } else {
            $averageLength = 0;
        }

    // Determine most booked property and count
    $mostBookedProperty = null;
    if (!$this->propertyFilter && $transactions->isNotEmpty()) {
        $propertyCounts = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->properties as $property) {
                $propertyName = $property->name_number;
                $propertyCounts[$propertyName] = ($propertyCounts[$propertyName] ?? 0) + 1;
            }
        }

        if (!empty($propertyCounts)) {
            arsort($propertyCounts);
            $topProperty = array_key_first($propertyCounts);
            $count = $propertyCounts[$topProperty];
            $mostBookedProperty = $topProperty . " ({$count} leases)";
        }
    }

    //----------------- CSV Filename
    $filename = 'Lease-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.csv';

    //Headers csv
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    //Define variables in CSV 
    return new StreamedResponse(function () use (
        $transactions,
        $totalLeases,
        $totalTenants,
        $mostBookedProperty,
        $totalAmountEarned
    ) {
        $handle = fopen('php://output', 'w');

        // CSV Header Row
        fputcsv($handle, [
            'Transaction ID',
            'Primary Tenant',
            'Property Rented',
            'Total Tenants', 
            'Start Date',
            'End Date',
            'Lease Duration',
            'Total Amount',
            'Status',
        ]);

        // Transaction rows
        foreach ($transactions as $transaction) {
            $properties = $transaction->properties->pluck('name_number')->implode(', ');

            $userName = optional($transaction->transactionUser)?->first_name . ' ' . optional($transaction->transactionUser)?->last_name ?? 'N/A';

            //Calculate months stay
            $start = Carbon::parse($transaction->start_datetime);
            $end = Carbon::parse($transaction->end_datetime);
            $leaseDurationMonths = $start->diffInMonths($end);

            fputcsv($handle, [
                $transaction->transaction_number,
                trim($userName),
                $properties,
                $transaction->pax,
                Carbon::parse($transaction->start_datetime)->format('F j, Y g:iA'),
                Carbon::parse($transaction->end_datetime)->format('F j, Y g:iA'),
                $leaseDurationMonths . ' month' . ($leaseDurationMonths > 1 ? 's' : ''),
                number_format($transaction->total_amount, 2),
                ucfirst($transaction->transaction_status),
            ]);
        }

        // Summary section
        fputcsv($handle, []); // blank line
        fputcsv($handle, ['Summary of Key Metrics']);
        fputcsv($handle, ['Total Leases Within Date Range:', $totalLeases . ' leases']);
        fputcsv($handle, ['Total Tenants:', $totalTenants . ' tenants']);
        fputcsv($handle, ['Most Booked Property:', $mostBookedProperty ?? 'N/A']);
        fputcsv($handle, ['Total Amount Earned:', 'PHP ' . number_format($totalAmountEarned, 2)]);

        fclose($handle);
    }, 200, $headers);

    }

    // ---------------------------- RENDER METHOD ------------------- //
    public function render()
    {
         //Query database, join tables for fks, and get all within date range
       $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id') //get properties
        ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id') //join trn_users for sort direction
        ->with(['transactionUser', 'properties', 'event_type'])
        ->where('reservation_type_id', 1) //3 for lease transactions
        ->when($this->start_date, function ($query) {
            // Parse input date for datetime variable
            $start = Carbon::parse($this->start_date)->startOfDay();
            $query->where('start_datetime', '>=', $start);
        })
        ->when($this->end_date, function ($query) {
            // end_date at the end of the day (23:59:59)
            $end = Carbon::parse($this->end_date)->endOfDay();
            $query->where('start_datetime', '<=', $end);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);

        return view('livewire.admin.reports.lease-reports', compact('transactions'));
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC"; // Toggle sorting direction
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC"; // Default sorting direction when changing columns
    }


    // ------------------------- EXPORT EXCEL METHOD --------------------------------- //
    public function exportLeaseExcel() {
         $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 1) // Lease type
        ->when($this->start_date, fn($q) => $q->where('start_datetime', '>=', Carbon::parse($this->start_date)->startOfDay()))
        ->when($this->end_date, fn($q) => $q->where('start_datetime', '<=', Carbon::parse($this->end_date)->endOfDay()))
        ->when($this->propertyFilter, fn($q) => $q->where('transaction_properties.property_id', $this->propertyFilter))
        ->when($this->propertyStatusFilter, fn($q) => $q->where('transaction_status', $this->propertyStatusFilter))
        ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
        ->get();

        //Define date variable
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        //Summary of calculations
        $totalLeases = $transactions->count();
        $totalTenants = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('total_amount');

        //Get average lease length
         $averageLength = 0;
        if ($totalLeases > 0) {
            $totalMonths = $transactions->sum(function ($t) {
                return Carbon::parse($t->start_datetime)->diffInMonths(Carbon::parse($t->end_datetime));
            });
            $averageLength = $totalMonths / $totalLeases;
        }

        //Most leased property
         $mostBookedProperty = null;
            if (!$this->propertyFilter && $transactions->isNotEmpty()) {
                $propertyCounts = [];
                foreach ($transactions as $t) {
                    foreach ($t->properties as $p) {
                        $propertyName = $p->name_number;
                        $propertyCounts[$propertyName] = ($propertyCounts[$propertyName] ?? 0) + 1;
                    }
                }
                if (!empty($propertyCounts)) {
                    arsort($propertyCounts);
                    $topProperty = array_key_first($propertyCounts);
                    $mostBookedProperty = $topProperty . " ({$propertyCounts[$topProperty]} leases)";
                }
    }

    $filename = 'Lease-Summary-' . Carbon::parse($start_date)->format('Ymd') . '-' . Carbon::parse($end_date)->format('Ymd') . '.xlsx';
    return new StreamedResponse(function() use ($transactions, $totalLeases, $totalTenants, $averageLength, $mostBookedProperty, $totalAmountEarned, $start_date, $end_date) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Logo ---
        $sheet->mergeCells('D1:D2');
        $drawing = new Drawing();
        $drawing->setPath(public_path('images/canopy-logo.png'));
        $drawing->setHeight(55);
        $drawing->setCoordinates('D1');
        $drawing->setOffsetX(10);
        $drawing->setWorksheet($sheet);

        // --- Header Section ---
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Canopy Farm PH');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(18)->getColor()->setRGB('166534');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A4:J4');
        $sheet->setCellValue('A4', '006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getFont()->setSize(11)->getColor()->setRGB('333333');

        $sheet->mergeCells('A5:J5');
        $sheet->setCellValue('A5', '+63 962 447 9893');
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(11)->getColor()->setRGB('333333');

        $sheet->mergeCells('A6:J6');
        $sheet->setCellValue('A6', 'Leases Summary');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('166534');
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- Reporting Period ---
        $sheet->mergeCells('A7:J7');
        if ($start_date && $end_date) {
            $reportingPeriod = 'Reporting Period: ' .
                Carbon::parse($start_date)->format('F d, Y') . ' – ' .
                Carbon::parse($end_date)->format('F d, Y');
        } else {
            $reportingPeriod = 'Reporting Period: All Records';
        }

        $sheet->setCellValue('A7', $reportingPeriod);
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('333333');
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- Header Row ---
        $headers = [
            'Transaction No.',
            'Tenant Name',
            'Propert Rented',
            'Lease Start Date',
            'Lease End Date',
            'Total Tenants',
            'Total Lease Amount',
            'Status'
        ];
        $sheet->fromArray($headers, null, 'A9');

        // --- Header Styling ---
        $headerStyle = $sheet->getStyle('A9:H9');
        $headerStyle->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('166534');
        $sheet->getRowDimension(9)->setRowHeight(25);

        // --- Transaction Rows ---
        $row = 10;
        foreach ($transactions as $t) {
            $properties = $t->properties->pluck('name_number')->implode(', ');
            $tenant = trim(optional($t->transactionUser)?->first_name . ' ' . optional($t->transactionUser)?->last_name) ?: 'N/A';

            $sheet->setCellValue("A{$row}", $t->transaction_number);
            $sheet->setCellValue("B{$row}", $tenant);
            $sheet->setCellValue("C{$row}", $properties);
            $sheet->setCellValue("D{$row}", Carbon::parse($t->start_datetime)->format('F j, Y'));
            $sheet->setCellValue("E{$row}", Carbon::parse($t->end_datetime)->format('F j, Y'));
            $sheet->setCellValue("F{$row}", $t->pax);
            $sheet->setCellValue("G{$row}", number_format($t->total_amount, 2));
            $sheet->setCellValue("H{$row}", ucfirst($t->transaction_status));

            $row++;
        }

        // --- Summary Section ---
        $row += 1;
        $sheet->setCellValue("A{$row}", 'Summary of Key Metrics');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('166534');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Leases Within Date Range:');
        $sheet->setCellValue("B{$row}", $totalLeases . ' leases');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Tenants:');
        $sheet->setCellValue("B{$row}", $totalTenants . ' tenants');
        $row++;

        $sheet->setCellValue("A{$row}", 'Average Lease Duration:');
        $sheet->setCellValue("B{$row}", round($averageLength, 1) . ' months');
        $row++;

        $sheet->setCellValue("A{$row}", 'Most Leased Property:');
        $sheet->setCellValue("B{$row}", $mostBookedProperty ?? 'N/A');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Amount Earned:');
        $sheet->setCellValue("B{$row}", 'PHP ' . number_format($totalAmountEarned, 2));

        // Highlight total amount
        $highlightRange = "A{$row}:B{$row}";
        $sheet->getStyle($highlightRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E6F4EA');
        $sheet->getStyle($highlightRange)->getFont()
            ->setBold(true)
            ->getColor()->setRGB('166534');

        // --- Auto-size Columns ---
        foreach (range('A', 'H') as $col) {
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
