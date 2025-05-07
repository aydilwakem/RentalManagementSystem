<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewLease extends Component
{
    public Transaction $transaction;
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteLease(Transaction $transaction)
    {
        if (!$transaction) {
            session()->flash('error', 'Lease not found!');
            return;
        }

        // Delete the tenant
        if ($this->confirmItemDelete) {
            $transaction->delete();
            $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'Lease successfully deleted!');

            // Redirect to the admin leases page
            return redirect()->route('admin.leases');
        }
    }

    //Get monthly rent for display:
        public function getMonthlyRent($transaction)
    {
        if (
            empty($transaction->start_datetime) ||
            empty($transaction->end_datetime) ||
            !is_numeric($transaction->total_amount) ||
            $transaction->total_amount <= 0
        ) {
            return 0;
        }
    
        $start = Carbon::parse($transaction->start_datetime)->startOfDay();
        $end = Carbon::parse($transaction->end_datetime)->startOfDay();
    
        if ($start->gt($end)) {
            return 0;
        }
    
        // Calculate the difference in months between the start and end date, inclusive of both months.
        $months = $start->diffInMonths($end) + 1;
    
        if ($months <= 0) {
            return 0;
        }
    
        return round($transaction->total_amount / $months, 2);
    }

    public function getMonthCount($startDatetime, $endDatetime)
    {
        //parse the end and start date 
        $start = Carbon::parse($startDatetime);
        $end = Carbon::parse($endDatetime);

        //get the number of months in between
        $months = $start->diffInMonths($end);

        //if the start and end are in the same month, we count it as 1
        if ($start->isSameMonth($end)) {
            return 1;
        }

        //add 1 to include the starting month
        return $months + 1; 
    }


    public function render()
    {
        return view('livewire.admin.properties.leases.view-lease');
    }
}
