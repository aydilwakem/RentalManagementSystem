<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventHall extends Component
{
    // Create a public property 
    public EventHall $eventHall;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
    // Function for deleting a record
    public function deleteEventHall(EventHall $eventHall)
    {
        if (!$eventHall) {
            session()->flash('error', 'Event Hall not found!');
            return;
        }

        if ($this->confirmItemDelete) {
            $eventHall->delete();
            $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'Event hall successfully deleted!');

            // Redirect to the admin event categories page
            return redirect()->route('admin.event-halls');
        }
    }

    public function render()
    {
        return view('livewire.admin.event-halls.view-event-hall');
    }
}
