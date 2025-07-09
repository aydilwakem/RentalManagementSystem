<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Property;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventReports extends Component
{
    // --------------------- DECLARATIONS -------------------------------------- //
    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 3; //Filter event transactions only
    public $sortBy = 'updated_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $perPage = 10;

    // ----------------------------- FOR DATE RANGES INPUT --------------------- //
    public $start_date;
    public $end_date;

    //------------------- FILTERS ------------------ //
    public $filteredTransactions = [];
    public $hallFilter = '';
    public $halls = []; 
    public $eventStatusFilter = '';
    public $filterApplied = false;


    // ------------------------------- MOUNT ---------------------------------- //
    public function mount(){
         // Initializes session variable if not already set
        if (!session()->has('fake_ids_event-reports')) {
            session(['fake_ids_event-reports' => []]);
        }

        // Set default date range to the current month
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->copy()->startOfMonth()->format('Y-m-d');
        $this->end_date = $now->copy()->endOfMonth()->format('Y-m-d');

        //Fetch all event halls
        $this->halls = Property::where('property_type_id', 3)->get();
    }


    // --------------------------- APPLY FILTER METHOD --------------------------- //
    public function applyEventFilter()
    {
        $query = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 3); //Fetch all events

       if ($this->start_date) {
            $query->whereDate('start_datetime', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('start_datetime', '<=', $this->end_date);
        }

        if ($this->hallFilter) {
            $query->where('transaction_properties.property_id', $this->hallFilter);
        }

        $query->when($this->eventStatusFilter, function ($query) {
            $query->where('transaction_status', $this->eventStatusFilter);
        });

        //use variable for filtering transactions
        $this->filteredTransactions = $query
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $this->filterApplied = true;

    }

    // ---------------------------- EXPORT PDF METHOD ------------------------------- //
    public function exportEventSummary()
    {
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->with(['transactionUser', 'properties', 'event_type'])
            ->where('reservation_type_id', 3) //3 for event transactions
            ->when($this->start_date, function ($query) {
                $start = Carbon::parse($this->start_date)->startOfDay();
                $query->where('start_datetime', '>=', $start);
            })
            ->when($this->end_date, function ($query) {
                $end = Carbon::parse($this->end_date)->endOfDay();
                $query->where('start_datetime', '<=', $end);
            })
            ->when($this->hallFilter, function ($query) { //Property filter
                $query->where('transaction_properties.property_id', $this->hallFilter);
            })
            ->when($this->eventStatusFilter, function ($query) { //Status filter
                $query->where('transaction_status', $this->eventStatusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

            $totalEvents = $transactions->count();
            $totalGuests = $transactions->sum('pax');
            $totalAmountEarned = $transactions->sum('total_amount');

            //----------------- Most Booked Hall within Date Range
            $mostBookedHall = null;

            if (!$this->hallFilter && $transactions->isNotEmpty()) {
            $hallCounts = [];

            foreach ($transactions as $transaction) {
                foreach ($transaction->properties as $property) {
                    $hallName = $property->name_number;

                    if (!isset($hallCounts[$hallName])) {
                        $hallCounts[$hallName] = 0;
                    }

                    $hallCounts[$hallName]++;
                }
            }

            if (!empty($hallCounts)) {
            arsort($hallCounts); // Sort descending by count
            $topHall = array_key_first($hallCounts);
            $count = $hallCounts[$topHall];

            $mostBookedHall = $topHall . ' (' . $count . ' events)';
            }

            //For checking
            Log::info("Most Booked Hall Count (from transactions):", [
            'hallCounts' => $hallCounts,
            'mostBookedHall' => $mostBookedHall,
            ]);
        }


        // ------------------- PDF VARIABLES ---------------- //
        $pdf = Pdf::loadView('livewire.admin.reports.events-report-summary', [
            'transactions' => $transactions,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'totalEvents' => $totalEvents,
            'totalGuests' => $totalGuests,
            'totalAmountEarned' => $totalAmountEarned,
            'halls' => $this->halls,
            'hallFilter' => $this->hallFilter, //Added variables to pdf 
            'eventStatusFilter' => $this->eventStatusFilter,
            'mostBookedHall' => $mostBookedHall //Pass variable to pdf for occupancy rate
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Event-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.pdf');
    }


    // ------------------------- EXPORT CSV METHOD ---------------//
    public function exportEventCsv(){
        //Query transactions and fetch all 
        
        $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 3) //Event ID
        ->when($this->start_date, function ($query) {
            $start = Carbon::parse($this->start_date)->startOfDay();
            $query->where('start_datetime', '>=', $start);
        })
        ->when($this->end_date, function ($query) {
            $end = Carbon::parse($this->end_date)->endOfDay();
            $query->where('start_datetime', '<=', $end);
        })
        ->when($this->hallFilter, function ($query) {
            $query->where('transaction_properties.property_id', $this->hallFilter);
        })
        ->when($this->eventStatusFilter, function ($query) {
            $query->where('transaction_status', $this->eventStatusFilter);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->get();

        //Calculations
        $totalEvents = $transactions->count();
        $totalGuests = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('total_amount');

        $mostBookedHall = null;
    if (!$this->hallFilter && $transactions->isNotEmpty()) {
        $hallCounts = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->properties as $property) {
                $hallName = $property->name_number;
                $hallCounts[$hallName] = ($hallCounts[$hallName] ?? 0) + 1;
            }
        }

        if (!empty($hallCounts)) {
            arsort($hallCounts);
            $topHall = array_key_first($hallCounts);
            $count = $hallCounts[$topHall];
            $mostBookedHall = $topHall . " ({$count} events)";
        }
    }

    //CSV Filename
    $filename = 'Event-Summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.csv';

    //Headers csv
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    //Define variables in CSV 
    return new StreamedResponse(function () use (
        $transactions,
        $totalEvents,
        $totalGuests,
        $mostBookedHall,
        $totalAmountEarned
    ) {
        $handle = fopen('php://output', 'w');

        // CSV Header Row
        fputcsv($handle, [
            'Transaction ID',
            'Booked By',
            'Company',
            'Event Hall',
            'Event Type', 
            'Guests', 
            'Start Date and Time',
            'End Date and Time',
            'Total Amount',
            'Status',
        ]);

        // Transaction rows
        foreach ($transactions as $transaction) {
            $halls = $transaction->properties->pluck('name_number')->implode(', ');

            $userName = optional($transaction->transactionUser)?->first_name . ' ' . optional($transaction->transactionUser)?->last_name ?? 'N/A';

            fputcsv($handle, [
                $transaction->transaction_number,
                trim($userName),
                $transaction->transactionUser->company_name,
                $halls,
                $transaction->event_type->name,
                $transaction->pax,
                Carbon::parse($transaction->start_datetime)->format('F j, Y g:iA'),
                Carbon::parse($transaction->end_datetime)->format('F j, Y g:iA'),
                number_format($transaction->total_amount, 2),
                ucfirst($transaction->transaction_status),
            ]);
        }

        // Summary section
        fputcsv($handle, []); // blank line
        fputcsv($handle, ['Summary of Key Metrics']);
        fputcsv($handle, ['Total Reservations Within Date Range:', $totalEvents . ' reservations']);
        fputcsv($handle, ['Total Guests:', $totalGuests . ' guests']);
        fputcsv($handle, ['Most Booked Hall:', $mostBookedHall ?? 'N/A']);
        fputcsv($handle, ['Total Amount Earned:', 'PHP ' . number_format($totalAmountEarned, 2)]);

        fclose($handle);
    }, 200, $headers);

    }







    // --------------------------- RENDER ------------------------------------------ //
    public function render()
    {
        //Query database, join tables for fks, and get all within date range
       $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id') //get properties
        ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id') //join trn_users for sort direction
        ->with(['transactionUser', 'properties', 'event_type'])
        ->where('reservation_type_id', 3) //3 for event transactions
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

        return view('livewire.admin.reports.event-reports', compact('transactions'));
    }

    //------------------------------------------ SORT BY FUNCTION ------------------------//
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
