<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\Event;
use App\Models\EventHall;
use App\Models\Property;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventHall extends Component
{
    // Create a public property 
    public Property $eventHall;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
    // Function for deleting a record
    public function deleteEventHall()
    {
        if ($this->confirmItemDelete) {
            $eventHall = Property::find($this->confirmItemDelete);

            if ($eventHall) {
                $eventHall->features()->detach();

                $eventHall->delete();

                $this->confirmItemDelete = false;

                session()->flash('message', 'Hall successfully deleted!');
            } else {
                session()->flash('error', 'Hall not found!');
            }
        }

        // Redirect to the admin houses page
        return redirect()->route('admin.event-halls');
    }

    public function render()
    {
        return view('livewire.admin.event-halls.view-event-hall');
    }
}
