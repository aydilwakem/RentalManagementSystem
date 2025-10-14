<?php

namespace App\Livewire\Admin\DayTours;

use Livewire\Component;
use App\Models\DayTour;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class ViewDayTours extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    
    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;
    
    public $selectedRows = [];
    public $selectPageRows = false;

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->dayTours
                ->pluck('id')
                ->map(function ($id) {
                    return (string) $id;
                })
                ->toArray();
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getDayToursProperty()
    {
        return DayTour::query()
            ->when($this->statusFilter, function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($this->perPage);
    }

    public function deleteSelectedRows()
    {
        try {
            // Check if any selected day tours have rates
            $dayToursWithRates = DayTour::whereIn('id', $this->selectedRows)
                ->whereHas('rates')
                ->exists();

            if ($dayToursWithRates) {
                $this->cannotDeleteItem = true;
                $this->confirmBulkDelete = false;
                return;
            }

            DayTour::whereIn('id', $this->selectedRows)->delete();
            $this->confirmBulkDelete = false;
            session()->flash('message', 'Selected day tours deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting day tours: ' . $e->getMessage());
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

    public function deleteDayTour()
    {
        $dayTour = DayTour::find($this->confirmItemDelete);

        if (!$dayTour) {
            session()->flash('error', 'Day Tour not found.');
            return;
        }

        if ($dayTour->rates()->exists()) {
            $this->cannotDeleteItem = true;
            $this->confirmItemDelete = null;
            return;
        }

        try {
            $dayTour->delete();
            $this->confirmItemDelete = null;
            session()->flash('message', 'Day Tour successfully deleted!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting day tour: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.day-tours.view-day-tours', [
            'dayTours' => $this->dayTours,
        ]);
    }
}