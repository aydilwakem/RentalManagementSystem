<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use App\Models\Property;
use App\Models\PropertyFeature;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app')]
class EditEventHall extends Component
{
    use WithFileUploads;

    public Property $eventHall;
    public $name_number;
    public $description;
    public $amount;
    public $capacity;
    public $extra_charge_per_hour;
    public $image;
    public $newImage;
    public $property_status; 
    public $eventHallId;
    public $features;            // All available features
    public $selectedFeatures = []; // Selected feature IDs

    public $confirmEditItem = false;
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    //To display info of selected item
    public function mount(Property $eventHall)
    {
        $this->eventHall = $eventHall;
        $this->eventHallId = $eventHall->id;
        $this->name_number = $eventHall->name_number;
        $this->description = $eventHall->description;
        $this->amount = $eventHall->amount;
        $this->capacity = $eventHall->capacity;
        $this->extra_charge_per_hour = $eventHall->extra_charge_per_hour;
        $this->property_status = $eventHall->property_status;
        $this->image = $eventHall->image;

        $this->features = PropertyFeature::all();
        $this->selectedFeatures = $eventHall->features()->pluck('property_features.id')->toArray();
    }

    public function updateEventHall()
    {
        try {
            $this->validate([
                'name_number' => "required|string|max:255|unique:properties,name_number,{$this->eventHallId},id",
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:1000|max:100000.00',
                'capacity' => 'required|numeric|min:20|max:200',
                'extra_charge_per_hour' => 'required|numeric|min:100|max:50000.00',
                'property_status' => 'required|in:available,booked,out_of_service',
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
            'name_number' => $this->name_number,
            'description' => $this->description,
            'amount' => $this->amount,
            'capacity' => $this->capacity,
            'extra_charge_per_hour' => $this->extra_charge_per_hour, 
            'property_status' => $this->property_status,
            'image' => $this->image,
        ]);

        $this->eventHall->features()->sync($this->selectedFeatures);

        session()->flash('message', 'Event Hall successfully updated!');
        $this->confirmEditItem = false;
        return redirect()->route('admin.event-halls');
    }

    
    public function render()
    {
        return view('livewire.admin.event-halls.edit-event-hall');
    }
}
