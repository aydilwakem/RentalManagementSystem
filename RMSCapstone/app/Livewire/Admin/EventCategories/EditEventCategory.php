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

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


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
        try{
        $this->validate([
            'name' => 'required|string|max:255|unique:prd_event_categories,name',
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048', // Ensure image size is within limit
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

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
