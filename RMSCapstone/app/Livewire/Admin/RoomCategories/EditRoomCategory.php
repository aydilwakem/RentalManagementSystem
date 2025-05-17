<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\PropertyCategory;
use App\Models\Amenity;
use Illuminate\Support\Facades\Storage;



#[Layout('layouts.app')]
class EditRoomCategory extends Component
{
    use WithFileUploads;

    public PropertyCategory $roomCategory; // Store the model received
    public $name;
    public $description;
    public $roomCategoryId;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    // Mount the fields to pre-fill the edit form
    public function mount(PropertyCategory $roomCategory)
    {
        $this->roomCategoryId = $roomCategory->id;
        $this->roomCategory = $roomCategory;
        $this->name = $roomCategory->name;
        $this->description = $roomCategory->description;
    }

    public function updateCategory()
    {
        try {
            $this->validate([
                'name' => "required|string|max:100|unique:property_categories,name,{$this->roomCategoryId},id",
                'description' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Update Room Category
        $this->roomCategory->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Room Category successfully updated!');

        return redirect()->route('admin.room-categories');
    }


    public function render()
    {
        return view('livewire.admin.room-categories.edit-room-category');
    }
}
