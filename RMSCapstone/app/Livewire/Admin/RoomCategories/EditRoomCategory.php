<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\RoomCategory;
use App\Models\Amenity;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditRoomCategory extends Component
{
    use WithFileUploads;

    public RoomCategory $roomCategory;
    public $name;
    public $description;
    public $image;
    public $newImage;
    public $selectedAmenities = [];
    public $amenities;

    public function mount(RoomCategory $roomCategory)
    {
        $this->roomCategory = $roomCategory;
        $this->name = $roomCategory->name;
        $this->description = $roomCategory->description;
        $this->image = $roomCategory->image;
        $this->selectedAmenities = $roomCategory->amenities->pluck('id')->toArray();
        $this->amenities = Amenity::all();
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048',
            'selectedAmenities' => 'array',
        ]);

        // Handle Image Upload
        if ($this->newImage) {
            if ($this->roomCategory->image) {
                Storage::disk('public')->delete($this->roomCategory->image);
            }
            $this->image = $this->newImage->store('room-categories', 'public');
        }

        // Update Room Category
        $this->roomCategory->update([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
        ]);

        // Sync selected amenities
        $this->roomCategory->amenities()->sync($this->selectedAmenities);

        session()->flash('message', 'Room Category successfully updated!');

        return redirect()->route('admin.room-categories');
    }

    public function render()
    {
        return view('livewire.admin.room-categories.edit-room-category');
    }
}
