<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Property;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
}
