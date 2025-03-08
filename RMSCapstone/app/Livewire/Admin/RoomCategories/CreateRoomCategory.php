<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RoomCategory;
use App\Models\Amenity;
use Illuminate\Support\Facades\Storage;

class CreateRoomCategory extends Component
{
    use WithFileUploads;

    public $name;
    public $description;

    public $image;

    public $selectedAmenities = [];
    public $amenities;

    public function mount()
    {
        $this->amenities = Amenity::all(); // Fetch all amenities
    }

    public function saveCategory()
    {
        // Validate form input (including image)
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Max 2MB image
            'selectedAmenities' => 'array',
        ]);

        // Store Image (if uploaded)
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('room-categories', 'public'); // Saves in storage/app/public/room-categories
        }

        // Create Room Category
        $roomCategory = RoomCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath, // Save path in DB
        ]);

        // Attach selected amenities via pivot table
        $roomCategory->amenities()->sync($this->selectedAmenities);

        // Reset form fields
        $this->reset(['name', 'description', 'image', 'selectedAmenities']);

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
