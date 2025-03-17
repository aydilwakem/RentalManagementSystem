<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditRoom extends Component
{
    use WithFileUploads;

    public Room $room;
    public $name;
    public $room_category_id;
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $turnover_duration;
    public $room_status;
    public $image;
    public $newImage;
    public $roomCategories; // Store room categories for dropdown

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->name = $room->name;
        $this->room_category_id = $room->room_category_id;
        $this->ideal_guest = $room->ideal_guest;
        $this->max_adults = $room->max_adults;
        $this->max_kids = $room->max_kids;
        $this->turnover_duration = $room->turnover_duration;
        $this->room_status = $room->room_status;
        $this->image = $room->image;
        $this->roomCategories = RoomCategory::all();
    }

    public function updateRoom()
    {
        $this->validate([
            'name' => 'nullable|string|max:255',
            'room_category_id' => 'nullable|exists:prd_room_categories,id',
            'ideal_guest' => 'nullable|integer|min:1',
            'max_adults' => 'nullable|integer|min:1',
            'max_kids' => 'nullable|integer|min:0',
            'turnover_duration' => 'nullable|integer|min:1',
            'room_status' => 'nullable|in:Available,Booked,Out of Service',
            'newImage' => 'nullable|image|max:2048',
        ]);

        // Handle image upload if a new one is selected
        if ($this->newImage) {
            if ($this->room->image) {
                Storage::disk('public')->delete($this->room->image);
            }
            $this->image = $this->newImage->store('rooms', 'public');
        }

        // Update room details
        $this->room->update([
            'name' => $this->name,
            'room_category_id' => $this->room_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'turnover_duration' => $this->turnover_duration,
            'room_status' => $this->room_status,
            'image' => $this->image,
        ]);

        session()->flash('message', 'Room successfully updated!');

        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.rooms.edit-room', [
            'roomCategories' => $this->roomCategories,
        ]);
    }
}
