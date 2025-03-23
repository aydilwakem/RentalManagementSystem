<?php

namespace App\Livewire\Admin\EventHalls;

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
        try {
            $eventHall = EventHall::find($this->confirmItemDelete);

            if (!$eventHall) {
                session()->flash('error', 'Event Category not found.');
                return;
            }

            $eventHall->delete(); // Attempt deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
            return redirect()->route('admin.event-halls');

        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Foreign key constraint violation
                $this->cannotDeleteItem = true; // Show the "Cannot Delete" modal
                $this->confirmItemDelete = null; // Close the confirmation modal
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.event-halls.view-event-hall');
    }
}
