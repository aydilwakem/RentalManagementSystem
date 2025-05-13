<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PropertyCategory;

class CreateRoomCategory extends Component
{
    use WithFileUploads;

    public $name;
    public $description;


    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }


    public function saveCategory()
    {
        try{
        // Validate form input (including image)
        $this->validate([
            'name' => 'required|string|max:255|unique:property_categories,name',
            'description' => 'nullable|string',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
                // If validation fails, close the modal
                $this->confirmCreateItem = false;
                throw $e;
            }

        // Create Room Category
        $roomCategory = PropertyCategory::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Reset form fields
        $this->reset(['name', 'description']);

        // Flash message for success
        session()->flash('message', 'Room Category successfully created!');

        // Redirect back to room categories list
        return redirect()->route('admin.room-categories');
    }


    public function render()
    {
        return view('livewire.admin.room-categories.create-room-category');
    }
}
