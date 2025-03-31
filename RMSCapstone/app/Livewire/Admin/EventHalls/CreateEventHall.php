<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateEventHall extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $amount;
    public $capacity;
    public $extra_charge_per_hr;
    public $image;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function saveEventHall()
    {
        try{
        // Validate form input (including image)
        $this->validate([
            'name' => 'required|string|max:255|unique:prd_event_halls,name',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:100|max:50000.00',
            'capacity' => 'required|numeric|min:10|max:200',
            'extra_charge_per_hr' => 'required|numeric|min:100|max:50000.00',
            'image' => 'nullable|image|max:1024', // Max 1MB image
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }

        // Ensure image upload is complete before storing
        if ($this->image && !$this->image->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Store Image (if uploaded)
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('event-halls', 'public'); // Saves in storage/app/public/event-halls
        }

        // Create Event Category
        $eventHall = EventHall::create([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'capacity' => $this->capacity,
            'extra_charge_per_hr' => $this->extra_charge_per_hr,
            'image' => $imagePath, // Save path in DB
        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'image', 'amount', 'capacity', 'extra_charge_per_hr']);

        // Flash message for success
        session()->flash('message', 'Event Hall successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.event-halls');
    }

    
    public function render()
    {
        return view('livewire.admin.event-halls.create-event-hall');
    }
}
