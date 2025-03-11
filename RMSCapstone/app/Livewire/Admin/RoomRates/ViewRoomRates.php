<?php

namespace App\Livewire\Admin\RoomRates;

use App\Models\RoomRate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewRoomRates extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDir = 'ASC';

    public $search = '';
    public $perPage = 5;
    public $statusFilter = ''; // Holds the selected room status


    public function deleteRoomRate($id)
    {
        // Find the room rate by ID
        $roomRate = RoomRate::find($id);

        if ($roomRate) {
            // Delete the room rate
            $roomRate->delete();

            // Flash success message
            session()->flash('message', 'Room Rate successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {

        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $roomRates = RoomRate::query()
            ->when($this->statusFilter, function ($query) {
                $query->where('rate_type', $this->statusFilter);
            })
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
        return view('livewire.admin.room-rates.view-room-rates', compact('roomRates'));
    }
}
