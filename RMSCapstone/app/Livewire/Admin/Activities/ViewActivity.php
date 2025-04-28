<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewActivity extends Component
{
    //Public declaration for activity variable
    public Activity $activity;

    //Public declaration of confirmation modal
    public $confirmItemDelete = false;

    //Method to make modal true by getting the item id
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    /**
     * Deletes an activity if confirmed.
     * - Checks if the activity exists before attempting deletion.
     * - If the deletion is confirmed, the activity is deleted and a success message is flashed.
     * - If the activity is not found, an error message is flashed.
     * - Redirects to the activities list after successful deletion.
     */
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
