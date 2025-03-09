<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Livewire\Component;

class ViewEventCategories extends Component
{
    
   public $eventCategories;

   public function deleteEventCategory($id)
    {
        // Find the room category by ID
        $eventCategory = EventCategory::find($id);

        if ($eventCategory) {
            // Delete the room category
            $eventCategory->delete();

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
        }
    }

    public function render()
    {
        //fetch all inputs form db
        $this->eventCategories = EventCategory::all();
        return view('livewire.admin.event-categories.view-event-categories');
    }
}
