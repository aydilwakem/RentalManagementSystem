<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Room;
use Livewire\WithPagination;

class ViewRooms extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortDir = 'ASC';

    public $search = '';
    public $perPage = 5;
    public $statusFilter = ''; // Holds the selected room status


    public function deleteRoom($id)
    {
        $room = Room::find($id);
        if ($room) {
            $room->delete();
            session()->flash('message', 'Room successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $rooms = Room::query()
            ->when($this->statusFilter, function ($query) {
                $query->where('room_status', $this->statusFilter);
            })
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.rooms.view-rooms', compact('rooms'));
    }
}
