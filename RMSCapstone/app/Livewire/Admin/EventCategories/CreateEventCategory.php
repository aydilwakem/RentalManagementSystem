<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateEventCategory extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $image;

    public function saveEventCategory()
    {
        // Validate form input (including image)
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:1024', // Max 1MB image
        ]);

        // Ensure image upload is complete before storing
        if ($this->image && !$this->image->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Store Image (if uploaded)
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('event-categories', 'public'); // Saves in storage/app/public/event-categories
        }

        // Create Event Category
        $eventCategory = EventCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath, // Save path in DB
        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'image']);

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
