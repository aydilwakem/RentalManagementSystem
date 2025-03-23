<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app')]
class EditEventHall extends Component
{
    use WithFileUploads;

    public EventHall $eventHall;
    public $name;
    public $description;
    public $amount;
    public $capacity;
    public $extra_charge_per_hr;
    public $image;
    public $newImage;

    public $confirmEditItem = false;
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    //To display info of selected item
    public function mount(EventHall $eventHall)
    {
        $this->eventHall = $eventHall;
        $this->name = $eventHall->name;
        $this->description = $eventHall->description;
        $this->amount = $eventHall->amount;
        $this->capacity = $eventHall->capacity;
        $this->extra_charge_per_hr = $eventHall->extra_charge_per_hr;
        $this->image = $eventHall->image;
    }

    public function updateEventHall()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:100|max:50000.00',
                'capacity' => 'required|numeric|min:10|max:200',
                'extra_charge_per_hr' => 'required|numeric|min:100|max:50000.00',
                'newImage' => 'nullable|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            if ($this->eventHall->image) {
                Storage::disk('public')->delete($this->eventHall->image);
            }

            //save the image in public folder
            $this->image = $this->newImage->store('event-halls', 'public');
        }

        // Update Event Hall
        $this->eventHall->update([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'capacity' => $this->capacity,
            'extra_charge_per_hr' => $this->extra_charge_per_hr, 
            'image' => $this->image,
        ]);

        session()->flash('message', 'Event Hall successfully updated!');
        $this->confirmEditItem = false;
        return redirect()->route('admin.event-halls');
    }

    
    public function render()
    {
        return view('livewire.admin.event-halls.edit-event-hall');
    }
}
