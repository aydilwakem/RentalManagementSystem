<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewLease extends Component
{
    public Transaction $transaction;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

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

        if ($this->confirmItemDelete) {
            if (in_array($transaction->transaction_status, ['done', 'terminated'])) {
                $transaction->delete();
                $this->confirmItemDelete = false;

                session()->flash('message', 'Lease successfully deleted!');
                return redirect()->route('admin.events');
            } else {
                // Set modal flag if event is not deletable
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = false;
            }
        }
    }

   
    public function exportLeaseDetails(){
         $pdf = Pdf::loadView('livewire.admin.properties.leases.lease-details', [
            'transaction' => $this->transaction,  // Pass the actual lease
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'lease-details-' . $this->transaction->start_datetime . '.pdf');
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
