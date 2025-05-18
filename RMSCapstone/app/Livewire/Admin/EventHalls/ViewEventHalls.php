<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\Event;
use App\Models\EventHall;
use App\Models\Property;
use App\Models\Transaction;
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
        ->where('name_number', 'like', '%' . trim($this->search) . '%') //mount name
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
        try {
        //Check active event halls
        $usedInTransactions = Transaction::whereHas('properties', function ($query) {
            $query->whereIn('property_id', $this->selectedRows);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Trigger modal
            $this->confirmBulkDelete = false;
            return;
        }

        // Detach features before deleting
        $properties = Property::whereIn('id', $this->selectedRows)->get();
        foreach ($properties as $property) {
            $property->features()->detach();
        }

        // Bulk Delete
        Property::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected halls got deleted!');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true; // FK error
        } else {
            throw $e; 
        }
    }
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
        $eventHall = Property::find($this->confirmItemDelete);

    if (!$eventHall) {
        session()->flash('error', 'Event Hall not found.');
        return;
    }

    // Check if the event hall is active in Events
    $usedInTransactions = Transaction::whereHas('properties', function ($query) use ($eventHall) {
        $query->where('property_id', $eventHall->id);
    })->exists();

    if ($usedInTransactions) {
        $this->cannotDeleteItem = true; // Show "Cannot delete" modal
        $this->confirmItemDelete = null; // Reset delete ID
        return;
    }

    try {
        $eventHall->features()->detach();

        // Delete the event hall
        $eventHall->delete();

        // Reset confirmation modal
        $this->confirmItemDelete = null;

        // Refresh the list of event halls and regenerate fake ids
        $eventHalls = Property::orderBy('created_at', 'ASC')->get();
        $fakeIDs = [];
        foreach ($eventHalls as $index => $hall) {
            $fakeIDs[$hall->id] = 'HALL-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_eventHalls' => $fakeIDs]);

        // Flash success message
        session()->flash('message', 'Event Hall successfully deleted!');
    }catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true;
        } else {
            throw $e;
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
