<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Room;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ViewRooms extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $perPage = 10;
    public $statusFilter = ''; // Holds the selected room status

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_rooms')) {
            session(['fake_ids_rooms' => []]);
        }
    }

    public function deleteRoom()
    {
        if ($this->confirmItemDelete) {
            // Find and delete the room
            Room::find($this->confirmItemDelete)?->delete();

            // Reset confirmation state
            $this->confirmItemDelete = false;

            // Fetch remaining rooms - sorted by creation date
            $room = Room::orderBy('created_at', 'ASC')->get();

            // Fetch remaining - sorted by creation date
            $room = Room::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($room as $index => $roomItem) {
                $fakeIDs[$roomItem->id] = 'RM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
            session(['fake_ids_rooms' => $fakeIDs]);
            session()->flash('message', 'Room successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    public function render()
    {
        $allRooms = Room::all();

        $rooms = Room::query()
            ->when($this->statusFilter, function ($query) {
                $query->where('room_status', $this->statusFilter);
            })
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // Retrieve unique session
        $fakeIDs = session('fake_ids_rooms', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Room::count()) {
            $fakeIDs = [];
            foreach (Room::orderBy('created_at', 'ASC')->get() as $index => $roomItem) {
                $fakeIDs[$roomItem->id] = 'RM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_rooms' => $fakeIDs]);
        }

        return view('livewire.admin.rooms.view-rooms', [
            'rooms' => $rooms,
            'fakeIDs' => $fakeIDs,
            'allRooms' => $allRooms,
        ]);
    }
}
