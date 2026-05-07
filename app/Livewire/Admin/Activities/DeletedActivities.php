<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Component;

class DeletedActivities extends Component
{
    //Public variable declaration to be used in methods
    public $deletedActivities;

    //Public declaration for delete item modal
    public $confirmItemDelete = false;

    //Method to make modal true
    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    //Method to call all soft deleted items
    public function mount()
    {
        $this->fetchDeletedActivities();
    }

    //Method to retrieve all soft deleted items and store them in the variable deletedActivities
    public function fetchDeletedActivities()
    {
        $this->deletedActivities = Activity::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    /**
    * Restore a soft-deleted activity 
    * by its ID and refresh the deleted activities list.
    */
    public function restoreActivity($activityId)
    {
        $activity = Activity::withTrashed()->find($activityId);
        if ($activity) {
            $activity->restore();
            session()->flash('message', 'Activity restored successfully.');
            $this->fetchDeletedActivities();
        }
    }

    /**
     * Permanently delete a soft-deleted activity 
     * by getting the item id and refresh the deleted activities list.
     */
    public function deleteActivityForever($activityId)
    {
        $activity = Activity::withTrashed()->find($this->confirmItemDelete);
        if ($activity) {
            $activity->forceDelete();
            session()->flash('message', 'Activity permanently deleted.');
            $this->fetchDeletedActivities();
        }
        //Closes the modal
        $this->confirmItemDelete = false;
    }

    /**
     * Render the deleted activities list in the page with generated fake IDs.
     * Fake IDs are generated to display user-friendly IDs for deleted activities.
    */
    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_activity', []);

        //Fetches all ids into array
        $deletedIds = $this->deletedActivities->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedActivities as $index => $activity) {
                $fakeIDs[$activity->id] = 'ACT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_activity' => $fakeIDs]);
        }

        return view('livewire.admin.activities.deleted-activities', [
            'deletedActivities' => $this->deletedActivities,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
