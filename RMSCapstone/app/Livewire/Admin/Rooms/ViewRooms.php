<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use App\Models\Property;
use App\Models\Transaction;
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

    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;

    //public declaration for bulk actions
    public $selectedRows = [];
    public $selectPageRows = false;

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->rooms
                ->pluck('id')
                ->map(function ($id) {
                    return (string) $id;
                })
                ->toArray();
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getRoomsProperty()
    {
        return Property::query()
            ->ofType('Room')
            ->when($this->statusFilter, function ($query) {
                $query->where('property_status', $this->statusFilter);
            })
            ->where(function ($query) {
                $search = trim($this->search);
                $query->where('name_number', 'like', '%' . $search . '%')->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    public function deleteSelectedRows()
    {
        try {
            //Check active event halls
            $usedInTransactions = Transaction::whereHas('properties', function ($query) {
                $query->whereIn('property_id', $this->selectedRows);
            })->exists();

            if ($usedInTransactions) {
                $this->cannotDeleteItem = true; // Trigger modal
                $this->confirmBulkDelete = false;
                return;
            }

            // Detach features before deleting
            $properties = Property::whereIn('id', $this->selectedRows)->get();
            foreach ($properties as $property) {
                $property->features()->detach();
            }

            // Bulk Delete
            Property::whereIn('id', $this->selectedRows)->delete();

            $this->confirmBulkDelete = false;
            session()->flash('message', 'All selected halls got deleted!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->cannotDeleteItem = true; // FK error
            } else {
                throw $e;
            }
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

    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_rooms')) {
            session(['fake_ids_rooms' => []]);
        }
    }

    public function deleteRoom()
    {
        $room = Property::find($this->confirmItemDelete);

        if (!$room) {
            session()->flash('error', 'Room not found.');
            return;
        }

        // Check if the event hall is active in Events
        $usedInTransactions = Transaction::whereHas('properties', function ($query) use ($room) {
            $query->where('property_id', $room->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Show "Cannot delete" modal
            $this->confirmItemDelete = null; // Reset delete ID
            return;
        }

        try {
            $room->features()->detach();

            // Delete the event hall
            $room->delete();

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Refresh the list of event halls and regenerate fake ids
            $rooms = Property::orderBy('created_at', 'ASC')->get();
            $fakeIDs = [];
            foreach ($rooms as $index => $room) {
                $fakeIDs[$room->id] = 'RM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in session
            session(['fake_ids_rooms' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'Room successfully deleted!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->cannotDeleteItem = true;
            } else {
                throw $e;
            }
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
        //select all rooms from property model
        $allRooms = Property::ofType('Room')->get();

        //query all rooms with the property status (available, booked, out)
        $rooms = $this->rooms;

        //Calculate fake IDs based on rooms sorted by created_at ASC
        $allSortedRooms = Property::ofType('Room')->orderBy('created_at', 'ASC')->get();

        $fakeIDs = [];
        foreach ($allSortedRooms as $index => $roomItem) {
            $fakeIDs[$roomItem->id] = 'RM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        session(['fake_ids_rooms' => $fakeIDs]);

        return view('livewire.admin.rooms.view-rooms', [
            'allRooms' => $allRooms,
            'rooms' => $rooms,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
