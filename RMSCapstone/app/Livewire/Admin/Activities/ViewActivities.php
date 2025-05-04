<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewActivities extends Component
{
    //Declarations for pagination and sorting
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    //Public declaration for delete confirmation modal
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false; 

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 


    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->activities->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 
    }

    public function getActivitiesProperty(){
        return Activity::query()
        ->where('name', 'like', "%{$this->search}%")
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
        Activity::whereIn('id', $this->selectedRows)->delete(); 
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected activities got deleted!');
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true; 
    }

    //Method to make modal true by getting item id
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    //Method to mount the sessions of fake ids
    public function mount()
    {
        // Ensure activities use a separate session key
        if (!session()->has('fake_ids_activities')) {
            session(['fake_ids_activities' => []]);
        }
    }

    /**
    * Delete an activity record if confirmed, update the list of activities,
    * reset fake IDs, and store the updated IDs in the session.
    */
    public function deleteActivity($id)
    {
        $activity = Activity::find($id);

        if ($activity) {

            if ($this->confirmItemDelete) {
                Activity::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;
            

             // Fetch remaining activities - sorted by creation date
             $activities = Activity::orderBy('created_at', 'ASC')->get();

             // Reset fake IDs
             $fakeIDs = [];
             foreach ($activities as $index => $act) {
                 $fakeIDs[$act->id] = 'ACT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
             }
 
             // Store updated fake IDs in a unique session key
            session(['fake_ids_activities' => $fakeIDs]);

            session()->flash('message', 'Activity successfully deleted!');
        }
    }
    }

    /**
     * Method to set the sorting direction for a given field.
     * If the field is already selected, the direction (ASC/DESC) will toggle.
     * If a new field is selected, it defaults to ASC.
     */
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    /**
     * Method to retrieve and paginate activities based on search query, with sorting.
     * - Retrieves activities matching the search term, ordered by the selected field and direction.
     * - Fetches the session data for fake IDs or recalculates them if the count mismatch.
     * - Stores the updated fake IDs in session for consistent use across the view.
     * 
     * Returns the view with the paginated activities and fake IDs for display.
     */
    public function render()
    {
        $activities = $this->activities; 
            // Retrieve unique session for activities
        $fakeIDs = session('fake_ids_activities', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Activity::count()) {
            $fakeIDs = [];
            foreach (Activity::orderBy('created_at', 'ASC')->get() as $index => $act) {
                $fakeIDs[$act->id] = 'ACT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_activities' => $fakeIDs]);
        }
       

        return view('livewire.admin.activities.view-activities', [
            'activities' => $activities,
            'fakeIDs' => $fakeIDs,
        ]);

    }
}
