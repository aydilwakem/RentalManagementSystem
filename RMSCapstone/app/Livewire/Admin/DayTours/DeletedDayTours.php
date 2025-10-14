<?php

namespace App\Livewire\Admin\DayTours;

use Livewire\Component;
use App\Models\DayTour;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class DeletedDayTours extends Component
{
    public $deletedDayTours;
    public $confirmItemDelete = false;

    public function mount()
    {
        $this->fetchDeletedDayTours();
    }

    public function fetchDeletedDayTours()
    {
        $this->deletedDayTours = DayTour::onlyTrashed()
            ->orderBy('deleted_at', 'DESC')
            ->get();
    }

    public function restoreDayTour($dayTourId)
    {
        $dayTour = DayTour::withTrashed()->find($dayTourId);
        if ($dayTour) {
            $dayTour->restore();
            session()->flash('message', 'Day Tour restored successfully.');
            $this->fetchDeletedDayTours();
        }
    }

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteDayTourForever()
    {
        $dayTour = DayTour::withTrashed()->find($this->confirmItemDelete);
        if ($dayTour) {
            $dayTour->forceDelete();
            session()->flash('message', 'Day Tour permanently deleted.');
            $this->fetchDeletedDayTours();
        }
        $this->confirmItemDelete = false;
    }

    public function render()
    {
        return view('livewire.admin.day-tours.deleted-day-tours', [
            'deletedDayTours' => $this->deletedDayTours,
        ]);
    }
}