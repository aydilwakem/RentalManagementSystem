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

   public function deleteEventHall($id)
    {
        // Find the event hall by ID
        $eventHall = EventHall::find($id);

        if ($eventHall) {
            // Delete the event hall
            $eventHall->delete();

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
        return view('livewire.admin.event-halls.view-event-halls', compact('eventHall'));
    }
}
