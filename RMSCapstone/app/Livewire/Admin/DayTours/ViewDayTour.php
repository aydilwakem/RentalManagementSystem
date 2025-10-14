<?php

namespace App\Livewire\Admin\DayTours;

use Livewire\Component;
use App\Models\DayTour;
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

    public function render()
    {
        return view('livewire.admin.day-tours.view-day-tour');
    }
}