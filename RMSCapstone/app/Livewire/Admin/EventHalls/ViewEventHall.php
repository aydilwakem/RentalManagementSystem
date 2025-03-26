<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\Event;
use App\Models\EventHall;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventHall extends Component
{
    // Create a public property 
    public EventHall $eventHall;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
    // Function for deleting a record
    public function deleteEventHall()
    {
            $eventHall = EventHall::find($this->confirmItemDelete);

            if (!$eventHall) {
                session()->flash('error', 'Event Category not found.');
                return;
            }

            // Check if the category is referenced in another table
            if (Event::where('event_hall_id', $eventHall->id)->exists()) { // Change 'Event' to your actual related model
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $eventHall->delete(); // Attempt deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
            return redirect()->route('admin.event-halls');

        }catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) { 
                $this->cannotDeleteItem = true; // Show the cannot delete modal
            } else {
                throw $e; // Re-throw other exceptions
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.event-halls.view-event-hall');
    }
}
