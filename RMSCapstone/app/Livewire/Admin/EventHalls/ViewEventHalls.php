<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\Event;
use App\Models\EventHall;
use App\Models\Property;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewEventHalls extends Component
{
    use WithPagination;

    public $eventHall; 

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $statusFilter = ''; // Holds the selected hall status

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint
    public $confirmBulkDelete = false; 

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->halls->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 
    }

    public function getHallsProperty(){
        return Property::query()
        ->ofType('Event Hall')
        ->when($this->statusFilter, function ($query) {
            $query->where('property_status', $this->statusFilter);
        })
        ->where('name_number', 'like', '%' . $this->search . '%') //mount name
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
        Property::whereIn('id', $this->selectedRows)->delete(); 
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected inclusions got deleted!');
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true; 
    }

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


    public function deleteEventHall()
    {
        if ($this->confirmItemDelete) {
            // Find the hall to be deleted
            $halls = Property::find($this->confirmItemDelete);

            if ($halls) {
                // Detach all amenities associated with this hall
                $halls->features()->detach();
                $halls->delete();

                // Reset confirmation state
                $this->confirmItemDelete = false;

                // Fetch remaining halls - sorted by creation date
                $halls = Property::orderBy('created_at', 'ASC')->get();

                // Reset fake IDs
                $fakeIDs = [];
                foreach ($halls as $index => $hall) {
                    $fakeIDs[$hall->id] = 'HALL-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                // Store updated fake IDs in a unique session key
                session(['fake_ids_eventHalls' => $fakeIDs]);

                // Flash success message
                session()->flash('message', 'Event Hall successfully deleted!');
            } else {
                // If room not found, flash an error message
                session()->flash('error', 'Event Hall not found!');
            }
        }
    }

    public function setSortBy($sortByField)
    {

        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
{
    //select all halls from property model
    $allHalls = Property::ofType('Event Hall')->get();

    //query all halls with the property status (available, booked, out)
    $halls = $this->halls; 

    //Calculate fake IDs based on rooms sorted by created_at ASC
    $allSortedHalls = Property::ofType('Event Hall')
        ->orderBy('created_at', 'ASC')
        ->get();

    $fakeIDs = [];
    foreach ($allSortedHalls as $index => $hall) {
        $fakeIDs[$hall->id] = 'HALL-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
    }

    session(['fake_ids_eventHalls' => $fakeIDs]);

    return view('livewire.admin.event-halls.view-event-halls', [
        'allHalls' => $allHalls,
        'halls' => $halls,
        'fakeIDs' => $fakeIDs,
    ]);
}
}
