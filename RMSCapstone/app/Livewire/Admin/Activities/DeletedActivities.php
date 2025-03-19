<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Component;

class DeletedActivities extends Component
{
    public $deletedActivities;

    public function mount()
    {
        $this->fetchDeletedActivities();
    }

    public function fetchDeletedActivities()
    {
        $this->deletedActivities = Activity::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreActivity($activityId)
    {
        $activity = Activity::withTrashed()->find($activityId);
        if ($activity) {
            $activity->restore();
            session()->flash('message', 'Amenity restored successfully.');
            $this->fetchDeletedActivities();
        }
    }

    public function deleteActivityForever($activityId)
    {
        $activity = Activity::withTrashed()->find($activityId);
        if ($activity) {
            $activity->forceDelete();
            session()->flash('message', 'Amenity permanently deleted.');
            $this->fetchDeletedActivities();
        }
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_activity', []);

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
