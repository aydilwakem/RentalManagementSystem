<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventCategory extends Component
{
     // Create a public property 
     public EventCategory $eventCategory;
 
     // Function for deleting a record
     public function deleteEventCategory(EventCategory $eventCategory)
     {
         if (!$eventCategory) {
             session()->flash('error', 'Event Category not found!');
             return;
         }
 
         if ($eventCategory) {
             // Delete the event category
             $eventCategory->delete();
 
             // Flash success message
             session()->flash('message', 'Event Category successfully deleted!');
 
             // Redirect to the admin event categories page
             return redirect()->route('admin.event-categories');
         }
     }


    public function render()
    {
        return view('livewire.admin.event-categories.view-event-category');
    }
}
