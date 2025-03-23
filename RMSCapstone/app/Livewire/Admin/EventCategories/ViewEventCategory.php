<?php

namespace App\Livewire\Admin\EventCategories;

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
        try {
            $eventCategory = EventCategory::find($this->confirmItemDelete);

            if (!$eventCategory) {
                session()->flash('error', 'Event Category not found.');
                return;
            }

            $eventCategory->delete(); // Attempt deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
            return redirect()->route('admin.event-categories');

        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Foreign key constraint violation
                $this->cannotDeleteItem = true; // Show the "Cannot Delete" modal
                $this->confirmItemDelete = null; // Close the confirmation modal
            }
        }
    }
         

    public function render()
    {
        return view('livewire.admin.event-categories.view-event-category');
    }
}
