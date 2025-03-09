<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewEventCategories extends Component
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

   public function deleteEventCategory($id)
    {
        // Find the room category by ID
        $eventCategory = EventCategory::find($id);

        if ($eventCategory) {
            // Delete the room category
            $eventCategory->delete();

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
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

        $eventCategories = EventCategory::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
        return view('livewire.admin.event-categories.view-event-categories', compact('eventCategories'));

        // old fetch code
        // //fetch all inputs form db
        // $this->eventCategories = EventCategory::all();
        // return view('livewire.admin.event-categories.view-event-categories');
    }
}
