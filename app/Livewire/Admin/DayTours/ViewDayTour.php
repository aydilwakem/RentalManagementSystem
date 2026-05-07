<?php

namespace App\Livewire\Admin\DayTours;

use Livewire\Component;
use App\Models\DayTour;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class ViewDayTour extends Component
{
    public DayTour $dayTour;
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function mount(DayTour $dayTour)
    {
        $this->dayTour = $dayTour->load('rates');
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteDayTour()
    {
        if ($this->confirmItemDelete) {
            $dayTour = DayTour::find($this->confirmItemDelete);

            if (!$dayTour) {
                session()->flash('error', 'Day Tour not found!');
                return redirect()->route('admin.day-tours');
            }

            // Check if day tour has rates (you can add more complex logic here)
            if ($dayTour->rates()->exists()) {
                $this->cannotDeleteItem = true;
                $this->confirmItemDelete = null;
                return;
            }

            $dayTour->delete();
            $this->confirmItemDelete = null;

            session()->flash('message', 'Day Tour successfully deleted!');
        }

        return redirect()->route('admin.day-tours');
    }

    //-------------------- COMPUTE CONVENIENCE FEE --------------------//
    public function computeConvenienceFeeTotal()
    {
        // Collects all payments related to this transaction
        $payments = $this->payments ?? collect();

        // Fetches all the convenience fee of the completed payments
        $total = $payments
            ->where('payment_status', 'completed')
            ->sum('convenience_fee');

        // Fallback if no payment was made yet
        if ($total == 0 && $this->transaction->convenience_fee > 0) {
            return $this->transaction->convenience_fee;
        }

        return $total;
    }

    //-------------------- DAY TOUR EXPORT TO PDF --------------------//
    public function exportDayTourDetails()
    {
        $transaction = Transaction::with([
            'invoice.payments',
            'transactionUser',
             'properties',
            'guestDetails',
        ])->findOrFail($this->transaction->id);

        $convenienceFeeTotal = $this->computeConvenienceFeeTotal();

        // $payments = $transaction->invoice->payments ?? collect();
        // $convenienceFeeTotal = $payments
        //     ->where('payment_status', 'completed')
        //     ->sum('convenience_fee');

        $pdf = Pdf::loadView('livewire.admin.reservations.reservation-details', [
            'transaction' => $transaction,  // Pass the actual transaction
            //Pass the relationships
            'guestDetails' => $transaction->guestDetails,
            'invoice' => $transaction->invoice,
            'payments' => $transaction->invoice->payments,
            'convenienceFeeTotal' => $convenienceFeeTotal,
            //'totalRooms' => $transaction->totalRooms,
            'properties' => $transaction->properties,
        ]);

        // Optional: Download directly or store then return URL
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Daytour-Details-' . $transaction->invoice->invoice_number . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.day-tours.view-day-tour');
    }
}