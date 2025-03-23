<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use Illuminate\Database\QueryException;
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

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint


    public function confirmDelete($id)
        {
            $this->confirmItemDelete = $id;
        }
    

    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_eventHalls')) {
            session(['fake_ids_eventHalls' => []]);
        }
    }


   //Function to delete an item, if there is constraint, modal will appear
   public function deleteEventHall()
   {
       //find id
   try {
       $eventHall = EventHall::find($this->confirmItemDelete);

       if (!$eventHall) {
           session()->flash('error', 'Event Category not found.');
           return;
       }

       $eventHall->delete(); //Attempt deletion

       // Reset confirmation modal to close it
       $this->confirmItemDelete = null;

       // Refresh event categories
       $eventHall = EventHall::orderBy('created_at', 'ASC')->get();

       // Reset fake IDs
       $fakeIDs = [];
       foreach ($eventHall as $index => $hall) {
           $fakeIDs[$hall->id] = 'ECT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
       }

       //Store session of the fake ids
       session(['fake_ids_eventHalls' => $fakeIDs]);

       // Flash success message
       session()->flash('message', 'Event Category successfully deleted!');

       //Catch for integrity constraint
   } catch (QueryException $e) {
       if ($e->getCode() == 23000) { // Foreign key constraint violation code
           $this->cannotDeleteItem = true; // Show the cannot delete modal
           $this->confirmItemDelete = null; // Close the confirmation modal
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
