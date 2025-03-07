<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use App\Models\RoomCategory;
use App\Models\Amenity;

class CreateRoomCategory extends Component
{
    public $name;
    public $description;
    public $selectedAmenities = [];
    public $amenities;

    public function mount()
    {
        $this->amenities = Amenity::all(); // Fetch all amenities
    }

    public function saveCategory()
    {
        // Validate form input
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selectedAmenities' => 'array',
        ]);

        // Create Room Category
        $roomCategory = RoomCategory::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Attach selected amenities via pivot table
        $roomCategory->amenities()->sync($this->selectedAmenities);

        // Reset form fields
        $this->reset(['name', 'description', 'selectedAmenities']);

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
