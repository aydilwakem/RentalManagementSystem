<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class ReservationReports extends Component
{
    public $statusFilter = ''; // Filter transactions by status
    public $reservation_type_id = 2;
    public $sortBy = 'updated_at';
    public $sortDir = 'DESC'; 
    public $search = '';
    public $perPage = 10;

    // ---FOR DATE RANGES INPUT ------ //
    public $start_date;
    public $end_date;
    


    public function mount()
    {
        // Initializes session variable if not already set
        if (!session()->has('fake_ids_transactions')) {
            session(['fake_ids_transactions' => []]);
        }
    }

    public function getTransactionsProperty()
    {
        //-------- Querying database to select all from transactions 
        //-------- Where it's in between dinput date ranges
        $query = Transaction::query();

        if ($this->start_date) {
            $query->whereDate('start_datetime', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('start_datetime', '<=', $this->end_date);
        }

        return $query
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();
    }

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
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $pdf = Pdf::loadView('livewire.admin.reports.reservations-report-summary', [
            'transactions' => $transactions,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reservation-summary-' . Carbon::parse($this->start_date)->format('Ymd') . '-' . Carbon::parse($this->end_date)->format('Ymd') . '.pdf');
    }


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
