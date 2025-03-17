<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewEventHalls extends Component
{
    use WithPagination;

    #[Url(history:true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $sortBy='created_at';

    #[Url(history:true)]
    public $sortDir='DESC';

    public function mount()
    {
        // Ensureit use a separate session key
        if (!session()->has('fake_ids_eventHalls')) {
            session(['fake_ids_eventHalls' => []]);
        }
    }


   public function deleteEventHall($id)
    {
        // Find the event hall by ID
        $eventHall = EventHall::find($id);

        if ($eventHall) {
            // Delete the event hall
            $eventHall->delete();

            // Fetch remaining - sorted by creation date
            $eventHall = EventHall::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($eventHall as $index => $hall) {
                $fakeIDs[$hall->id] = 'HALL-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
           session(['fake_ids_eventHalls' => $fakeIDs]);


            // Flash success message
            session()->flash('message', 'Event Hall successfully deleted!');
        }
    }

    public function setSortBy($sortByField){

        if($this->sortBy == $sortByField){
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return ;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $eventHall = EventHall::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

             // Retrieve unique session for halls
            $fakeIDs = session('fake_ids_eventHalls', []);

            // Recalculate fake IDs if count mismatches
            if (count($fakeIDs) !== EventHall::count()) {
                $fakeIDs = [];
                foreach (EventHall::orderBy('created_at', 'ASC')->get() as $index => $hall) {
                    $fakeIDs[$hall->id] = 'HALL-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }
                session(['fake_ids_eventHalls' => $fakeIDs]);
            }

            

        return view('livewire.admin.event-halls.view-event-halls', [
            'eventHall' => $eventHall,
            'fakeIDs' => $fakeIDs,

        ]); 
    }
}
