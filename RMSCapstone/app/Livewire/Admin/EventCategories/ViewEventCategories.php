<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewEventCategories extends Component
{
    use WithPagination;

    #[Url(history:true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history:true)]
    public $sortBy='created_at';

    #[Url(history:true)]
    public $sortDir='DESC';

    public $confirmItemDelete = false; //Modal for delete confirmation
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
        {
            $this->confirmItemDelete = $id;
        }

    public function mount()
    {
        // Ensure categories use a separate session key
        if (!session()->has('fake_ids_eventCategory')) {
            session(['fake_ids_eventCategory' => []]);
        }
    }

//Function to delete an item, if there is constraint, modal will appear
public function deleteEventCategory()
{
        $eventCategory = EventType::find($this->confirmItemDelete);

        if (!$eventCategory) {
            session()->flash('error', 'Event Category not found.');
            return;
        }

        // Check if the category is referenced in another table
        if (Transaction::where('event_type_id', $eventCategory->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $eventCategory->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Refresh event categories
            $eventCategories = EventType::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($eventCategories as $index => $category) {
                $fakeIDs[$category->id] = 'ECT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store session of the fake IDs
            session(['fake_ids_eventCategory' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
        }catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) { 
                $this->cannotDeleteItem = true; // Show the cannot delete modal
            } else {
                throw $e; // Re-throw other exceptions
            }
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

        $eventCategories = EventType::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

             // Retrieve unique session 
            $fakeIDs = session('fake_ids_eventCategory', []);

            // Recalculate fake IDs if count mismatches
            if (count($fakeIDs) !== EventType::count()) {
                $fakeIDs = [];
                foreach (EventType::orderBy('created_at', 'ASC')->get() as $index => $category) {
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
