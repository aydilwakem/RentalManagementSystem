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

    public function deleteActivity($id)
    {
        $activity = Activity::find($id);

        if ($activity) {
            $activity->delete();
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

        return view('livewire.admin.activities.view-activities', compact('activities'));
    }
}
