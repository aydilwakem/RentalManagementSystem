<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomCategory;

class CreateRoom extends Component
{
    use WithFileUploads;

    public $name;
    public $room_category_id;
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $turnover_duration;
    public $room_status = 'Available'; // Default value
    public $image;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public $roomCategories; // To store fetched room categories

    public function mount()
    {
        $this->roomCategories = RoomCategory::all(); // Fetch all categories
    }

    public function saveRoom()
    {
        try {
            // Validate the form input
            $this->validate([
                'name' => 'required|string|max:255',
                'room_category_id' => 'required|exists:prd_room_categories,id',
                'ideal_guest' => 'required|integer|min:1',
                'max_adults' => 'required|integer|min:1',
                'max_kids' => 'required|integer|min:0',
                'turnover_duration' => 'required|string',
                'room_status' => 'required|in:Available,Booked,Out of Service',
                'image' => 'nullable|image|max:1024', // Max 1MB image
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Ensure image upload is complete before storing
        $imagePath = null;
        if ($this->image) {
            if (!$this->image->isValid()) {
                session()->flash('error', 'Image upload failed. Please try again.');
                return;
            }
            $imagePath = $this->image->store('rooms', 'public'); // Saves in storage/app/public/rooms
        }

        // Create new room
        Room::create([
            'name' => $this->name,
            'room_category_id' => $this->room_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'turnover_duration' => $this->turnover_duration,
            'room_status' => $this->room_status,
            'image' => $imagePath, // Store image path in DB
        ]);

        // Reset form fields
        $this->reset(['name', 'room_category_id', 'ideal_guest', 'max_adults', 'max_kids', 'turnover_duration', 'room_status', 'image']);

        // Flash success message
        session()->flash('message', 'Room successfully created!');

        // Redirect back to rooms list
        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.rooms.create-room', [
            'roomCategories' => $this->roomCategories, // Pass categories to view
        ]);
    }
}
