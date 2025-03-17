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

    public function mount()
    {
        // Ensure categories use a separate session key
        if (!session()->has('fake_ids_eventCategory')) {
            session(['fake_ids_eventCategory' => []]);
        }
    }


   public function deleteEventCategory($id)
    {
        // Find the room category by ID
        $eventCategory = EventCategory::find($id);

        if ($eventCategory) {
            // Delete the room category
            $eventCategory->delete();

            // Fetch remaining - sorted by creation date
            $eventCategory = EventCategory::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($eventCategory as $index => $category) {
                $fakeIDs[$category->id] = 'ECT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
           session(['fake_ids_eventCategory' => $fakeIDs]);

 

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

             // Retrieve unique session 
            $fakeIDs = session('fake_ids_eventCategory', []);

            // Recalculate fake IDs if count mismatches
            if (count($fakeIDs) !== EventCategory::count()) {
                $fakeIDs = [];
                foreach (EventCategory::orderBy('created_at', 'ASC')->get() as $index => $category) {
                    $fakeIDs[$category->id] = 'ECT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }
                session(['fake_ids_eventCategory' => $fakeIDs]);
            }

        
        return view('livewire.admin.event-categories.view-event-categories',[
            'eventCategories' => $eventCategories, 
            'fakeIDs' => $fakeIDs,
           
        ]); 

        
    }
}
