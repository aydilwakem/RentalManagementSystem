<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventCategory extends Component
{
     // Create a public property 
     public EventCategory $eventCategory;

    
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
     // Function for deleting a record
    public function deleteEventCategory()
    {
            $eventCategory = EventCategory::find($this->confirmItemDelete);

            if (!$eventCategory) {
                session()->flash('error', 'Event Category not found.');
                return;
            }

            // Check if the category is referenced in another table
        if (Event::where('event_category_id', $eventCategory->id)->exists()) { // Change 'Event' to your actual related model
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

            $eventCategory->delete(); // Attempt deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
            return redirect()->route('admin.event-categories');

        }
         

    public function render()
    {
        return view('livewire.admin.event-categories.view-event-category');
    }
}
