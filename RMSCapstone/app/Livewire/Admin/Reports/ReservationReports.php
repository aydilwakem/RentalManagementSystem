<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Property;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReservationReports extends Component
{
    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 2;
    public $sortBy = 'updated_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $perPage = 10;

    //------------------- FILTERS
    public $filteredTransactions = [];
    public $roomFilter = '';
    public $rooms = [];
    public $reservationStatusFilter = '';
    public $filterApplied = false;

    //-------------------- GUEST DETAILS
    public $country_of_origin = [];

    // ---FOR DATE RANGES INPUT ------ //
    public $start_date;
    public $end_date;


    //------------------------ MOUNT METHOD --------------------//
    public function mount()
    {
        $this->rooms = Property::where('property_type_id', 1)->get();

        // Initializes session variable if not already set
        if (!session()->has('fake_ids_transactions')) {
            session(['fake_ids_transactions' => []]);
        }

        //Default date range current month
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->copy()->startOfMonth()->format(('Y-m-d'));
        $this->end_date = $now->copy()->endOfMonth()->format(('Y-m-d'));
    }

    //------------------------ FILTER BUTTON METHOD ------------------------//
    /**
     * Filter
     *
     * Queries the database by doing table joins on all transaction
     * tables using where and filter variables
     *
     */

    public function applyReservationFilter()
    {
        $query = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 2);

    if ($this->start_date) {
        $query->whereDate('start_datetime', '>=', $this->start_date);
    }

    if ($this->end_date) {
        $query->whereDate('start_datetime', '<=', $this->end_date);
    }

    if ($this->roomFilter) {
        $query->where('transaction_properties.property_id', $this->roomFilter);
    }

    $query->when($this->reservationStatusFilter, function ($query) {
        $query->where('transaction_status', $this->reservationStatusFilter);
    });

    //use variable for filtering transactions
    $this->filteredTransactions = $query
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();

    $this->filterApplied = true;

    }

    //----------------------- EXPORT PDF METHOD ------------------------------------- //

    /**
     * EXPORT PDF
     *
     * Queries the database and exports a DOM PDF File
     * to a dedicated pdf blade
     *
     *
     */
    public function exportReservationSummary()
    {
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 2)
            ->when($this->start_date, function ($query) {
                $start = Carbon::parse($this->start_date)->startOfDay();
                $query->where('start_datetime', '>=', $start);
            })
            ->when($this->end_date, function ($query) {
                $end = Carbon::parse($this->end_date)->endOfDay();
                $query->where('start_datetime', '<=', $end);
            })
            ->when($this->roomFilter, function ($query) { //Property filter
                $query->where('transaction_properties.property_id', $this->roomFilter);
            })
            ->when($this->reservationStatusFilter, function ($query) { //Status filter
            $query->where('transaction_status', $this->reservationStatusFilter);
        })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $totalReservations = $transactions->count();

        // Calculate average reservation length (in nights)
        if ($totalReservations > 0) {
            $totalNights = $transactions->sum(function($transaction) {
                $start = Carbon::parse($transaction->start_datetime);
                $end = Carbon::parse($transaction->end_datetime);
                return $start->diffInDays($end); // nights count
            });

            $averageLength = $totalNights / $totalReservations;
        } else {
            $averageLength = 0;
        }

        $totalGuests = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('total_amount');


        // -------------- Most Booked Room within Date Range ----------------------- //
     $mostBookedRoom = null;

        if (!$this->roomFilter && $transactions->isNotEmpty()) {
            $roomCounts = [];

            foreach ($transactions as $transaction) {
                foreach ($transaction->properties as $property) {
                    $roomName = $property->name_number;

                    if (!isset($roomCounts[$roomName])) {
                        $roomCounts[$roomName] = 0;
                    }

                    $roomCounts[$roomName]++;
                }
        }

        if (!empty($roomCounts)) {
            arsort($roomCounts); // Sort descending by count
            $topRoom = array_key_first($roomCounts);
            $count = $roomCounts[$topRoom];

            $mostBookedRoom = $topRoom . ' (' . $count . ' bookings)';
    }

    Log::info("Most Booked Room Count (from transactions):", [
        'roomCounts' => $roomCounts,
        'mostBookedRoom' => $mostBookedRoom,
    ]);
}


    // ----------------------- PDF Variables --------------------- //

        //Passes all necessary variables to be defined in the blade
        $pdf = Pdf::loadView('livewire.admin.reports.reservations-report-summary', [
            'transactions' => $transactions,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'totalReservations' => $totalReservations,
            'averageLength' => round($averageLength, 2),
            'totalAmountEarned' => $totalAmountEarned,
            'totalGuests' => $totalGuests,
            'rooms' => $this->rooms,
            'roomFilter' => $this->roomFilter, //Added variables to pdf
            'reservationStatusFilter' => $this->reservationStatusFilter,
            'mostBookedRoom' => $mostBookedRoom //Pass variable to pdf for occupancy rate
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Reservation-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.pdf');
    }




    // -------------------------- EXPORT CSV METHOD ------------------------------------ //
    public function exportReservationCsv()
    {
    // Fetch transactions using query
    $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 2)
        ->when($this->start_date, function ($query) {
            $start = Carbon::parse($this->start_date)->startOfDay();
            $query->where('start_datetime', '>=', $start);
        })
        ->when($this->end_date, function ($query) {
            $end = Carbon::parse($this->end_date)->endOfDay();
            $query->where('start_datetime', '<=', $end);
        })
        ->when($this->roomFilter, function ($query) {
            $query->where('transaction_properties.property_id', $this->roomFilter);
        })
        ->when($this->reservationStatusFilter, function ($query) {
            $query->where('transaction_status', $this->reservationStatusFilter);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();

    // Calculations
    $totalReservations = $transactions->count();
    $totalGuests = $transactions->sum('pax');
    $totalAmountEarned = $transactions->sum('total_amount');

    //Calculate average night stay
    $averageLength = 0;
    if ($totalReservations > 0) {
        $totalNights = $transactions->sum(function ($transaction) {
            return Carbon::parse($transaction->start_datetime)->diffInDays(Carbon::parse($transaction->end_datetime));
        });
        $averageLength = $totalNights / $totalReservations;
    }

    // Determine most booked room and count
    $mostBookedRoom = null;
    if (!$this->roomFilter && $transactions->isNotEmpty()) {
        $roomCounts = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->properties as $property) {
                $roomName = $property->name_number;
                $roomCounts[$roomName] = ($roomCounts[$roomName] ?? 0) + 1;
            }
        }

        if (!empty($roomCounts)) {
            arsort($roomCounts);
            $topRoom = array_key_first($roomCounts);
            $count = $roomCounts[$topRoom];
            $mostBookedRoom = $topRoom . " ({$count} bookings)";
        }
    }

    // CSV Filename
    $filename = 'Reservation-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    //Define variables
    return new StreamedResponse(function () use (
        $transactions,
        $totalReservations,
        $totalGuests,
        $averageLength,
        $mostBookedRoom,
        $totalAmountEarned
    ) {
        $handle = fopen('php://output', 'w');

        // CSV Header Row
        fputcsv($handle, [
            'Transaction ID',
            'Guest',
            'Rooms',
            'Check-in Date',
            'Check-out Date',
            'Total Guests',
            'Status',
            'Total Amount'
        ]);

        // Transaction rows
        foreach ($transactions as $transaction) {
            $rooms = $transaction->properties->pluck('name_number')->implode(', ');

            $userName = optional($transaction->transactionUser)?->first_name . ' ' . optional($transaction->transactionUser)?->last_name ?? 'N/A';

            fputcsv($handle, [
                $transaction->transaction_number,
                trim($userName),
                $rooms,
                Carbon::parse($transaction->start_datetime)->format('Y-m-d'),
                Carbon::parse($transaction->end_datetime)->format('Y-m-d'),
                $transaction->pax,
                ucfirst($transaction->transaction_status),
                number_format($transaction->total_amount, 2),
            ]);
        }

        // Summary section
        fputcsv($handle, []); // blank line
        fputcsv($handle, ['Summary of Key Metrics']);
        fputcsv($handle, ['Total Reservations Within Date Range:', $totalReservations . ' reservations']);
        fputcsv($handle, ['Total Guests:', $totalGuests . ' guests']);
        fputcsv($handle, ['Average Reservation Length (nights):', round($averageLength, 1) . ' nights']);
        fputcsv($handle, ['Most Booked Room:', $mostBookedRoom ?? 'N/A']);
        fputcsv($handle, ['Total Amount Earned:', 'PHP ' . number_format($totalAmountEarned, 2)]);

        fclose($handle);
    }, 200, $headers);
}


    //-------------------- EXPORT GUEST DETAILS ----------------------//
    public function exportGuestDetailsSummary()
{
    $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties', 'guestDetails']) // eager load main + companions
        ->where('reservation_type_id', 2)
        ->when($this->start_date, function ($query) {
            $start = Carbon::parse($this->start_date)->startOfDay();
            $query->where('start_datetime', '>=', $start);
        })
        ->when($this->end_date, function ($query) {
            $end = Carbon::parse($this->end_date)->endOfDay();
            $query->where('start_datetime', '<=', $end);
        })
        ->when($this->roomFilter, function ($query) {
            $query->where('transaction_properties.property_id', $this->roomFilter);
        })
        ->when($this->reservationStatusFilter, function ($query) {
            $query->where('transaction_status', $this->reservationStatusFilter);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();

    // ------------------- SUMMARY CALCULATIONS ------------------- //
    $totalReservations = $transactions->count();
    $totalGuests = 0;
    $totalFilipinos = 0;
    $totalForeigners = 0;
    $totalGuestNights = 0;
    $totalNights = 0;
    $guestByCountry = [];

    foreach ($transactions as $transaction) {
        $start = Carbon::parse($transaction->start_datetime);
        $end = Carbon::parse($transaction->end_datetime);
        $nights = max(1, $start->diffInDays($end)); // at least 1 night

        // count reservation nights
        $totalNights += $nights;

        // ---------------- Main Guest ----------------
        $mainCountry = $transaction->transactionUser->country ?? 'Unknown';
        $totalGuests += 1;
        $totalGuestNights += $nights;

        if (strtolower($mainCountry) === 'philippines') {
            $totalFilipinos++;
        } else {
            $totalForeigners++;
        }

        if (!isset($guestByCountry[$mainCountry])) {
            $guestByCountry[$mainCountry] = [
                'checkins' => 0,
            ];
        }
        $guestByCountry[$mainCountry]['checkins'] += 1;

        // ---------------- Accompanying Guests ----------------
        foreach ($transaction->guestDetails as $guest) {
            $country = $guest->country_of_origin ?? 'Unknown';
            $totalGuests += 1;
            $totalGuestNights += $nights;

            if (strtolower($country) === 'philippines') {
                $totalFilipinos++;
            } else {
                $totalForeigners++;
            }

            if (!isset($guestByCountry[$country])) {
                $guestByCountry[$country] = [
                    'checkins' => 0,
                ];
            }
            $guestByCountry[$country]['checkins'] += 1;
        }
    }

    // Sort by country alphabetically
    ksort($guestByCountry);

    // ----------------------- PDF ----------------------- //
    $pdf = Pdf::loadView('livewire.admin.reports.guest-details-summary', [
        'transactions' => $transactions,
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'totalReservations' => $totalReservations,
        'totalNights' => $totalNights,
        'totalGuests' => $totalGuests,
        'totalFilipinos' => $totalFilipinos,
        'totalForeigners' => $totalForeigners,
        'guestByCountry' => $guestByCountry,
        'roomFilter' => $this->roomFilter,
        'rooms' => $this->rooms,
        'reservationStatusFilter' => $this->reservationStatusFilter,
    ]);

    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'Guest-Details-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.pdf');
    }





    // -------------------------------- RENDER METHOD ------------------------- //
    public function render()
    {
        //Query database, join tables for fks, and get all within date range
       $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id') //join trn_users for sort direction
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 2)
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



        return view('livewire.admin.reports.reservation-reports', compact('transactions'));
    }

    //--------------------------- SORT BY FUNCTION -----------//
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC"; // Toggle sorting direction
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC"; // Default sorting direction when changing columns
    }



    //----------------------- EXPORT EXCEL METHOD ------------------------------------- //

    /**
     * EXPORT EXCEL FILE
     *
     * Queries the database and exports a xlsx File
     *
     *
     *
     */

    public function exportReservationExcel(){
        $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 2) // Reservation type
        ->when($this->start_date, fn($q) => $q->where('start_datetime', '>=', Carbon::parse($this->start_date)->startOfDay()))
        ->when($this->end_date, fn($q) => $q->where('start_datetime', '<=', Carbon::parse($this->end_date)->endOfDay()))
        ->when($this->roomFilter, fn($q) => $q->where('transaction_properties.property_id', $this->roomFilter))
        ->when($this->reservationStatusFilter, fn($q) => $q->where('transaction_status', $this->reservationStatusFilter))
        ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
        ->get();

        //Define dates
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        //Calculate all summaries
        $totalReservations = $transactions->count();
        $totalGuests = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('total_amount');

        //Get average length of stay
        $averageLength = 0;
        if ($totalReservations > 0) {
            $totalNights = $transactions->sum(function ($t) {
                return Carbon::parse($t->start_datetime)->diffInDays(Carbon::parse($t->end_datetime));
            });
            $averageLength = $totalNights / $totalReservations;
        }

        //Get most booked room
        $mostBookedRoom = null;
        if (!$this->roomFilter && $transactions->isNotEmpty()) {
            $roomCounts = [];
            foreach ($transactions as $t) {
                foreach ($t->properties as $p) {
                    $roomName = $p->name_number;
                    $roomCounts[$roomName] = ($roomCounts[$roomName] ?? 0) + 1;
                }
            }
            if (!empty($roomCounts)) {
                arsort($roomCounts);
                $topRoom = array_key_first($roomCounts);
                $mostBookedRoom = $topRoom . " ({$roomCounts[$topRoom]} bookings)";
            }
        }

        //Define file name
        $filename = 'Reservation-Summary-' . Carbon::parse($start_date)->format('Ymd') . '-' . Carbon::parse($end_date)->format('Ymd') . '.xlsx';

    return new StreamedResponse(function() use ($transactions, $totalReservations, $totalGuests, $averageLength, $mostBookedRoom, $totalAmountEarned, $start_date, $end_date) {

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
        $sheet->setCellValue('A6', 'Reservations Summary');
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
            'Booked By',
            'Room(s)',
            'Check-In Date',
            'Check-Out Date',
            'Total Guests',
            'Total Amount',
            'Status'
        ];
        $sheet->fromArray($headers, null, 'A9');

        // --- Header Styling ---
        $headerStyle = $sheet->getStyle('A9:H9');
        $headerStyle->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('166534');
        $sheet->getRowDimension(9)->setRowHeight(25);

        // --- Rows ---
        $row = 10;
        foreach ($transactions as $t) {
            $rooms = $t->properties->pluck('name_number')->implode(', ');
            $bookedBy = trim(optional($t->transactionUser)?->first_name . ' ' . optional($t->transactionUser)?->last_name) ?: 'N/A';

            $sheet->setCellValue("A{$row}", $t->transaction_number);
            $sheet->setCellValue("B{$row}", $bookedBy);
            $sheet->setCellValue("C{$row}", $rooms);
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

        $sheet->setCellValue("A{$row}", 'Total Reservations Within Date Range:');
        $sheet->setCellValue("B{$row}", $totalReservations . ' reservations');
        $row++;

        $sheet->setCellValue("A{$row}", 'Total Guests:');
        $sheet->setCellValue("B{$row}", $totalGuests . ' guests');
        $row++;

        $sheet->setCellValue("A{$row}", 'Average Length of Stay:');
        $sheet->setCellValue("B{$row}", round($averageLength, 1) . ' nights');
        $row++;

        $sheet->setCellValue("A{$row}", 'Most Booked Room:');
        $sheet->setCellValue("B{$row}", $mostBookedRoom ?? 'N/A');
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

        $summaryStart = $row - 5;
        $summaryEnd = $row;

        $summaryRange = "A{$summaryStart}:B{$summaryEnd}";

        $sheet->getStyle($summaryRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

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
