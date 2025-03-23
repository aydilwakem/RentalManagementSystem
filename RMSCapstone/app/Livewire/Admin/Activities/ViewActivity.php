<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewActivity extends Component
{
    // Create a public property 
    public Activity $activity;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    //For deleting the record
    public function deleteActivity(Activity $activity)
    {
    if (!$activity) {
        session()->flash('error', 'Activity not found!');
        return;
    }

    if ($this->confirmItemDelete) {
        $activity->delete();
        $this->confirmItemDelete = false;

        session()->flash('message', 'Activity successfully deleted!');
        return redirect()->route('admin.activities');
    }
    }


    public function render()
    {
        return view('livewire.admin.activities.view-activity');
    }
}
