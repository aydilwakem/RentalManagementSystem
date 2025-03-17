<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditEventCategory extends Component
{
    use WithFileUploads;

    public EventCategory $eventCategory;
    public $name;
    public $description;
    public $image;
    public $newImage;

    //To display info of selected item
    public function mount(EventCategory $eventCategory)
    {
        $this->eventCategory = $eventCategory;
        $this->name = $eventCategory->name;
        $this->description = $eventCategory->description;
        $this->image = $eventCategory->image;
    }

    public function updateEventCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048', // Ensure image size is within limit
        ]);

        // Ensure the image is uploaded properly
        if ($this->newImage && !$this->newImage->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Handle Image Upload
        if ($this->newImage) {
            if ($this->eventCategory->image) {
                Storage::disk('public')->delete($this->eventCategory->image);
            }

            //save the image in public folder
            $this->image = $this->newImage->store('event-categories', 'public');
        }

        // Update Event Category
        $this->eventCategory->update([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
        ]);

        session()->flash('message', 'Event Category successfully updated!');

        return redirect()->route('admin.event-categories');
    }

    public function render()
    {
        return view('livewire.admin.event-categories.edit-event-category');
    }
}
