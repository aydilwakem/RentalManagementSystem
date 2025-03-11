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

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();
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

        return view('livewire.admin.events.view-events', compact('event'));
    }
}
