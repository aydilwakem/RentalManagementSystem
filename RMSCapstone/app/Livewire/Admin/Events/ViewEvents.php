<?php

namespace App\Livewire\Admin\Events;

use App\Models\EventType;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
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
    public $transactionStatus = '';

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;
    public $eventTypes; 

    public $halls; 
    public $guests; 

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        // Ensure events use a separate session key
        if (!session()->has('fake_ids_event-list')) {
            session(['fake_ids_event-list' => []]);
        }

        $this->eventTypes = EventType::all();
        $this->halls = Property::ofType('Event Hall')->where('property_status', 'available')->get();
        $this->guests = TransactionUser::where('trn_user_type', 'guest')->get();
    }

    public function deleteEvent()
    {
       if ($this->confirmItemDelete) {
        $event = Transaction::find($this->confirmItemDelete);

        if ($event && in_array($event->transaction_status, ['done', 'terminated'])) {
            $event->delete();

            // Recalculate fake IDs only for reservation_type_id = 3
            $fakeIDs = [];
            foreach (
                Transaction::where('reservation_type_id', 3)
                    ->orderBy('created_at', 'ASC')
                    ->get() as $index => $eventItem
            ) {
                $fakeIDs[$eventItem->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            session(['fake_ids_event-list' => $fakeIDs]);

            session()->flash('message', 'Event successfully deleted!');
        } else {
            // Show modal instead of flash
            $this->cannotDeleteItem = true;
        }

        $this->confirmItemDelete = false;
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
            ->select('trn_transactions.*')
            ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
            ->join('trn_users', 'trn_transactions.created_by', '=', 'trn_users.id') //join trn_users for sort direction
            ->with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 3)
            ->when($this->search !== '', function ($query) {
            $search = '%' . $this->search . '%';
            $query->whereHas('transactionUser', function ($subQuery) use ($search) {
                $subQuery->where('first_name', 'like', $search)
                         ->orWhere('last_name', 'like', $search)
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search]);
            });
        })
            ->when($this->transactionStatus !== '', function ($query) {
                $query->where('transaction_status', $this->transactionStatus);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // For fake IDs: get all event-type transactions ordered by creation
            $eventTransactions = Transaction::where('reservation_type_id', 3)
                ->orderBy('created_at', 'ASC')
                ->get();

            $fakeIDs = session('fake_ids_event-list', []);

            // Recalculate if count mismatch
            if (count($fakeIDs) !== $eventTransactions->count()) {
                $fakeIDs = [];
                foreach ($eventTransactions as $index => $eventItem) {
                    $fakeIDs[$eventItem->id] = 'EVT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }
                session(['fake_ids_event-list' => $fakeIDs]);
            }

        return view('livewire.admin.events.view-events', [
            'event' => $event,
            'allEvents' => $allEvents,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
