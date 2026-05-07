<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventType;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateEventCategory extends Component
{
    use WithFileUploads;
    
    //Public declaration of fillable fields
    public $name;
    public $description;

    //Public variable declaration of create confirmation modal
    public $confirmCreateItem = false;

    //Method to make modal true
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    /**
     * Creates a new event category.
     * - Validates input fields and handles failed validation.
     * - Stores uploaded image if present.
     * - Saves the event category record and resets form fields.
     * - Flashes success message and redirects to the event categories list.
    */
    public function saveEventCategory()
    {
        try{
        // Validate form input (including image)
        $this->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/|unique:event_types,name',
            'description' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }


        // Create Event Category
        $eventCategory = EventType::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Reset form fields
        $this->reset(['name', 'description']);

        // Flash message for success
        session()->flash('message', 'Event Category successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.event-categories');
    }

    public function render()
    {
        return view('livewire.admin.event-categories.create-event-category');
    }
}
