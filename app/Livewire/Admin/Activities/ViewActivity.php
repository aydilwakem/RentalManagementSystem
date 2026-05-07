<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewActivity extends Component
{
    //Public declaration for activity variable
    public Activity $activity;

    //Public declaration of confirmation modal
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

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
    public function deleteActivity()
    {
         if ($this->confirmItemDelete) {
        $activity = Activity::find($this->confirmItemDelete);

        if (!$activity) {
            session()->flash('error', 'Activity not found!');
            return redirect()->route('admin.activities');
        }

        // Check if the event hall is linked to any transaction
        $usedInTransactions = Transaction::whereHas('activities', function ($query) use ($activity) {
            $query->where('activity_id', $activity->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete because hall is active in Transactions
            $this->confirmItemDelete = null;
            return;
        }

        // Delete the hall
        $activity->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Activity successfully deleted!');
        }

        return redirect()->route('admin.activities');
    }

    public function render()
    {
        return view('livewire.admin.activities.view-activity');
    }
}
