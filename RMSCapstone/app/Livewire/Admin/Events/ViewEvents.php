<?php

namespace App\Livewire\Admin\Events;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewEvents extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';
    public $eventStatus = '';
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        // Ensure activities use a separate session key
        if (!session()->has('fake_ids_events')) {
            session(['fake_ids_events' => []]);
        }
    }

    public function deleteEvent()
    {
        if ($this->confirmItemDelete) {
            // Find and delete the event
            Transaction::find($this->confirmItemDelete)?->delete();

            // Reset confirmation state
            $this->confirmItemDelete = false;

            // Recalculate fake IDs
            $fakeIDs = [];
            foreach (Transaction::orderBy('created_at', 'ASC')->get() as $index => $eventItem) {
                $fakeIDs[$eventItem->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in session
            session(['fake_ids_events' => $fakeIDs]);

            // Flash message for user feedback
            session()->flash('message', 'Event successfully deleted!');
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
        $allEvents = Transaction::all();

        $event = Transaction::query()
            // ->search($this->search)
            ->where('reservation_type_id', 3)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // Retrieve unique session
        $fakeIDs = session('fake_ids_events', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Transaction::count()) {
            $fakeIDs = [];
            foreach (Transaction::orderBy('created_at', 'ASC')->get() as $index => $eventItem) {
                $fakeIDs[$eventItem->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_events' => $fakeIDs]);
        }

        return view('livewire.admin.events.view-events', [
            'event' => $event,
            'allEvents' => $allEvents,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
