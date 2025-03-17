<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewEvents extends Component
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

    public $eventStatus = ''; 

    public function mount()
    {
        // Ensure activities use a separate session key
        if (!session()->has('fake_ids_events')) {
            session(['fake_ids_events' => []]);
        }
    }


    public function deleteEvent($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();

             // Fetch remaining - sorted by creation date
             $event = Event::orderBy('created_at', 'ASC')->get();

             // Reset fake IDs
             $fakeIDs = [];
             foreach ($event as $index => $eventItem) {
                 $fakeIDs[$eventItem->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
             }
 
             // Store updated fake IDs in a unique session key
            session(['fake_ids_events' => $fakeIDs]);
            session()->flash('message', 'Event successfully deleted!');
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
        $event = Event::query()
            ->search($this->search)
            ->when($this->eventStatus !== '', function($query){
                $query->where('status', $this->eventStatus);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

            // Retrieve unique session 
        $fakeIDs = session('fake_ids_events', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Event::count()) {
            $fakeIDs = [];
            foreach (Event::orderBy('created_at', 'ASC')->get() as $index => $eventItem) {
                $fakeIDs[$eventItem->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_events' => $fakeIDs]);
        }

        return view('livewire.admin.events.view-events', [
            'event' => $event, 
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
