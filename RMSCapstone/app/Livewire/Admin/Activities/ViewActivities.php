<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewActivities extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public function mount()
    {
        // Ensure activities use a separate session key
        if (!session()->has('fake_ids_activities')) {
            session(['fake_ids_activities' => []]);
        }
    }

    public function deleteActivity($id)
    {
        $activity = Activity::find($id);

        if ($activity) {
            $activity->delete();

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
        $activities = Activity::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

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
