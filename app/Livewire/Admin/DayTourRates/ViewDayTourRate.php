<?php

namespace App\Livewire\Admin\DayTourRates;

use Livewire\Component;
use App\Models\DayTourRate;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class ViewDayTourRate extends Component
{
    public DayTourRate $dayTourRate;
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function mount(DayTourRate $dayTourRate)
    {
        $this->dayTourRate = $dayTourRate->load('dayTour');
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteDayTourRate()
    {
        if ($this->confirmItemDelete) {
            $rate = DayTourRate::find($this->confirmItemDelete);

            if (!$rate) {
                session()->flash('error', 'Day Tour Rate not found!');
                return redirect()->route('admin.day-tour-rates');
            }

            // Check if rate is being used in transactions (you can add this logic later)
            // if ($rate->transactions()->exists()) {
            //     $this->cannotDeleteItem = true;
            //     $this->confirmItemDelete = null;
            //     return;
            // }

            $rate->delete();
            $this->confirmItemDelete = null;

            session()->flash('message', 'Day Tour Rate successfully deleted!');
        }

        return redirect()->route('admin.day-tour-rates');
    }

    public function render()
    {
        return view('livewire.admin.day-tour-rates.view-day-tour-rate');
    }
}