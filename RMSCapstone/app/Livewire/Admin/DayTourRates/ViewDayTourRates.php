<?php

namespace App\Livewire\Admin\DayTourRates;

use Livewire\Component;
use App\Models\DayTourRate;
use App\Models\DayTour;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class ViewDayTourRates extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $dayTourFilter = '';
    public $rateTypeFilter = '';
    public $dayTypeFilter = '';
    public $statusFilter = '';
    
    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;
    
    public $selectedRows = [];
    public $selectPageRows = false;

    public $dayTours;

    public function mount()
    {
        $this->dayTours = DayTour::active()->get();
    }

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->dayTourRates
                ->pluck('id')
                ->map(function ($id) {
                    return (string) $id;
                })
                ->toArray();
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getDayTourRatesProperty()
    {
        return DayTourRate::query()
            ->with('dayTour')
            ->when($this->dayTourFilter, function ($query) {
                $query->where('day_tour_id', $this->dayTourFilter);
            })
            ->when($this->rateTypeFilter, function ($query) {
                $query->where('rate_type', $this->rateTypeFilter);
            })
            ->when($this->dayTypeFilter, function ($query) {
                $query->where('day_type', $this->dayTypeFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('rate_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('dayTour', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($this->perPage);
    }

    public function deleteSelectedRows()
    {
        try {
            // Check if any selected rates are being used (you can add this logic later)
            // $ratesInUse = DayTourRate::whereIn('id', $this->selectedRows)
            //     ->whereHas('transactions')
            //     ->exists();

            // if ($ratesInUse) {
            //     $this->cannotDeleteItem = true;
            //     $this->confirmBulkDelete = false;
            //     return;
            // }

            DayTourRate::whereIn('id', $this->selectedRows)->delete();
            $this->confirmBulkDelete = false;
            session()->flash('message', 'Selected day tour rates deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting day tour rates: ' . $e->getMessage());
        }
    }

    public function confirmDeleteInBulk()
    {
        $this->confirmBulkDelete = true;
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteDayTourRate()
    {
        $rate = DayTourRate::find($this->confirmItemDelete);

        if (!$rate) {
            session()->flash('error', 'Day Tour Rate not found.');
            return;
        }

        // Check if rate is being used (you can add this logic later)
        // if ($rate->transactions()->exists()) {
        //     $this->cannotDeleteItem = true;
        //     $this->confirmItemDelete = null;
        //     return;
        // }

        try {
            $rate->delete();
            $this->confirmItemDelete = null;
            session()->flash('message', 'Day Tour Rate successfully deleted!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting day tour rate: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.day-tour-rates.view-day-tour-rates', [
            'dayTourRates' => $this->dayTourRates,
        ]);
    }
}