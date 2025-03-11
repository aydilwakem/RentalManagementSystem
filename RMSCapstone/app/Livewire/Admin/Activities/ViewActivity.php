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

    // Function for deleting a record
    public function deleteActivity(Activity $activity)
    {
        if (!$activity) {
            session()->flash('error', 'Activity not found!');
            return;
        }

        if ($activity) {
            // Delete the activity
            $activity->delete();

            // Flash success message
            session()->flash('message', 'Activity successfully deleted!');

            // Redirect to the admin activities page
            return redirect()->route('admin.activities');
        }
    }

    public function render()
    {
        return view('livewire.admin.activities.view-activity');
    }
}
