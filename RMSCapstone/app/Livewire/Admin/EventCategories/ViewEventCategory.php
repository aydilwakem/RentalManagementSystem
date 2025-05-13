<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewEventCategory extends Component
{
     // Create a public property 
    public EventType $eventCategory;

    
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }
 
     // Function for deleting a record
    public function deleteEventCategory()
    {
            $eventCategory = EventType::find($this->confirmItemDelete);

            if (!$eventCategory) {
                session()->flash('error', 'Event Category not found.');
                return;
            }

            // Check if the category is referenced in another table
        if (Transaction::where('event_type_id', $eventCategory->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $eventCategory->delete(); // Attempt deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Flash success message
            session()->flash('message', 'Event Category successfully deleted!');
            return redirect()->route('admin.event-categories');

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
        return view('livewire.admin.event-categories.view-event-category');
    }
}
