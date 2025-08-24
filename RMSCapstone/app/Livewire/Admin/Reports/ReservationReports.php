<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Property;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
}
