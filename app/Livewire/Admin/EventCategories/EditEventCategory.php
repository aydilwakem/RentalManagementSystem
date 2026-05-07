<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditEventCategory extends Component
{
    use WithFileUploads;

    public EventType $eventCategory;
    public $name;
    public $description;
    public $eventCategoryId; 

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    //To display info of selected item
    public function mount(EventType $eventCategory)
    {
        $this->eventCategory = $eventCategory;
        $this->eventCategoryId = $eventCategory->id; 
        $this->name = $eventCategory->name;
        $this->description = $eventCategory->description;
    }

    public function updateEventCategory()
    {
        try{
        $this->validate([
            'name' => "required|string|max:255|regex:/^[A-Za-z\s\-]+$/|unique:event_types,name,{$this->eventCategoryId},id",
            'description' => 'nullable|string|regex:/^[A-Za-z\s\-]+$/',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

        // Update Event Category
        $this->eventCategory->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Event Category successfully updated!');

        return redirect()->route('admin.event-categories');
    }

    public function render()
    {
        return view('livewire.admin.event-categories.edit-event-category');
    }
}
