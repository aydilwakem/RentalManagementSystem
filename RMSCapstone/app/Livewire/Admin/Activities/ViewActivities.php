<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewActivities extends Component
{
    //-------------------------------------- DECLARATIONS --------------------------------- //
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

   //-------------------------------------- MODALS --------------------------------- //
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;
    public $confirmBulkDelete = false;
    public $selectedItemId = null;

    //------------------------------------ BULK ACTIONS --------------------------------- //
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
        ->where('name', 'like', '%' . trim($this->search) . '%')
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
       try {
        //Check active event halls
        $usedInTransactions = Transaction::whereHas('activities', function ($query) {
            $query->whereIn('activity_id', $this->selectedRows);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Trigger modal
            $this->confirmBulkDelete = false;
            return;
        }

        // Bulk Delete
        Activity::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected activities got deleted!');
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

    //------------------------------------ MODAL METHOD --------------------------------- //
    public function confirmDelete($id)
    {
        $this->selectedItemId = $id;
        $this->confirmItemDelete = true;
    }

    //-------------------------------------- FAKE IDS --------------------------------- //
    public function mount()
    {
        // Ensure activities use a separate session key
        if (!session()->has('fake_ids_activities')) {
            session(['fake_ids_activities' => []]);
        }
    }

    //--------------------------------- DELETE ACTIVITY METHOD --------------------------------- //
    /**
    * Delete an activity record if confirmed that it's not in used in transactions, update the list of activities,
    * reset fake IDs, and store the updated IDs in the session.
    */
    public function deleteActivity()
    {
        $activity = Activity::find($this->selectedItemId);

    if (!$activity) {
        session()->flash('error', 'Activity not found.');
        return;
    }

    // Check if the event hall is active in Transactions
    $usedInTransactions = Transaction::whereHas('activities', function ($query) use ($activity) {
        $query->where('activity_id', $activity->id);
    })->exists();

    if ($usedInTransactions) {
        $this->cannotDeleteItem = true; // Show "Cannot delete" modal
        $this->confirmItemDelete = null; // Reset delete ID
        return;
    }

    try {

        // Delete the event hall
        $activity->delete();

        // Reset confirmation modal
        $this->confirmItemDelete = false;
        $this->selectedItemId = null;

        // Refresh the list of event halls and regenerate fake ids
        $activities = Activity::orderBy('created_at', 'ASC')->get();
        $fakeIDs = [];
        foreach ($activities as $index => $activity) {
            $fakeIDs[$activity->id] = 'ACT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_activities' => $fakeIDs]);

        // Flash success message
        session()->flash('message', 'Activity successfully deleted!');
    }catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true;
        } else {
            throw $e;
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
