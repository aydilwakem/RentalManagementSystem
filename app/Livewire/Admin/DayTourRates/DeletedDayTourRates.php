<?php

namespace App\Livewire\Admin\DayTourRates;

use Livewire\Component;
use App\Models\DayTourRate;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class DeletedDayTourRates extends Component
{
    public $deletedDayTourRates;
    public $confirmItemDelete = false;

    public function mount()
    {
        $this->fetchDeletedDayTourRates();
    }

    public function fetchDeletedDayTourRates()
    {
        $this->deletedDayTourRates = DayTourRate::onlyTrashed()
            ->with('dayTour')
            ->orderBy('deleted_at', 'DESC')
            ->get();
    }

    public function restoreDayTourRate($rateId)
    {
        $rate = DayTourRate::withTrashed()->find($rateId);
        if ($rate) {
            $rate->restore();
            session()->flash('message', 'Day Tour Rate restored successfully.');
            $this->fetchDeletedDayTourRates();
        }
    }

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteDayTourRateForever()
    {
        $rate = DayTourRate::withTrashed()->find($this->confirmItemDelete);
        if ($rate) {
            $rate->forceDelete();
            session()->flash('message', 'Day Tour Rate permanently deleted.');
            $this->fetchDeletedDayTourRates();
        }
        $this->confirmItemDelete = false;
    }

    public function render()
    {
        return view('livewire.admin.day-tour-rates.deleted-day-tour-rates', [
            'deletedDayTourRates' => $this->deletedDayTourRates,
        ]);
    }
}