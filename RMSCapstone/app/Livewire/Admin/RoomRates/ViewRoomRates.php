<?php

namespace App\Livewire\Admin\RoomRates;

use App\Models\RoomRate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewRoomRates extends Component
{
    use WithPagination;

    #[Url(history:true)]
    public $sortBy = 'created_at';

    #[Url(history:true)]
    public $sortDir = 'DESC';
    
    #[Url(history:true)]
    public $search = '';
    public $perPage = 5;
    public $statusFilter = ''; // Holds the selected room status


    public function mount()
    {
        // Ensure use a separate session key
        if (!session()->has('fake_ids_roomRate')) {
            session(['fake_ids_roomRate' => []]);
        }
    }
    public function deleteRoomRate($id)
    {
        // Find the room rate by ID
        $roomRate = RoomRate::find($id);

        if ($roomRate) {
            // Delete the room rate
            $roomRate->delete();

            // Fetch remaining - sorted by creation date
             $roomRate = RoomRate::orderBy('created_at', 'ASC')->get();

             // Reset fake IDs
             $fakeIDs = [];
             foreach ($roomRate as $index => $rate) {
                 $fakeIDs[$rate->id] = 'RATE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
             }
 
             // Store updated fake IDs in a unique session key
            session(['fake_ids_roomRate' => $fakeIDs]);


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

            // Retrieve unique session 
        $fakeIDs = session('fake_ids_roomRate', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== RoomRate::count()) {
            $fakeIDs = [];
            foreach (RoomRate::orderBy('created_at', 'ASC')->get() as $index => $rate) {
                $fakeIDs[$rate->id] = 'RATE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_roomRate' => $fakeIDs]);
        }


        return view('livewire.admin.room-rates.view-room-rates', [
            'roomRates' => $roomRates, 
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
