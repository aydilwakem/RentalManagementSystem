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
    public $newImages = [];
    public $storedImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteIndex = null;
    public $property_status;
    public $eventHallId;
    public $features;            // All available features
    public $selectedFeatures = []; // Selected feature IDs
    public $inclusions;

    public $confirmEditItem = false;
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    //To display info of selected item
    public function mount(Property $eventHall)
    {
        //Only mount inclusions
        $this->inclusions = PropertyFeature::where('property_type_id', 3)->get();

        $this->eventHall = $eventHall;
        $this->eventHallId = $eventHall->id;
        $this->name_number = $eventHall->name_number;
        $this->description = $eventHall->description;
        $this->amount = $eventHall->amount;
        $this->capacity = $eventHall->capacity;
        $this->extra_charge_per_hour = $eventHall->extra_charge_per_hour;
        $this->property_status = $eventHall->property_status;
        $this->image = $eventHall->image;
        $this->storedImages = $eventHall->images ?? [];

        $this->features = PropertyFeature::all();
        $this->selectedFeatures = $eventHall->features()->pluck('property_features.id')->toArray();
    }

    public function updateEventHall()
    {
        try {
            $this->validate([
                'name_number' => "required|string|max:255|unique:properties,name_number,{$this->eventHallId},id",
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:10000|max:100000.00',
                'capacity' => 'required|numeric|min:20|max:200',
                'extra_charge_per_hour' => 'required|numeric|min:1000|max:50000.00',
                'property_status' => 'required|in:available,booked,out_of_service',
                'newImage' => 'nullable|image|max:2048',
                'newImages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // Handle image upload if a new one is selected
        $newImagePaths = [];
        if (!empty($this->newImages)) {
            foreach ($this->newImages as $image) {
                if ($image->isValid()) {
                    $path = $image->store('event-halls', 'public');
                    $newImagePaths[] = $path;
                }
            }
        }

        // Merge old and new images
        $allImages = array_merge($this->storedImages, $newImagePaths);

        // Update Event Hall
        $this->eventHall->update([
            'name_number' => $this->name_number,
            'description' => $this->description,
            'amount' => $this->amount,
            'capacity' => $this->capacity,
            'extra_charge_per_hour' => $this->extra_charge_per_hour,
            'property_status' => $this->property_status,
            'image' => $this->image,
            'images' => $allImages,
        ]);

        $this->eventHall->features()->sync($this->selectedFeatures);

        session()->flash('message', 'Event Hall successfully updated!');
        $this->confirmEditItem = false;
        return redirect()->route('admin.event-halls');
    }

    public function confirmImageDelete($index)
    {
        $this->imageToDeleteIndex = $index;
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
    {
        if (isset($this->storedImages[$this->imageToDeleteIndex])) {
            Storage::disk('public')->delete($this->storedImages[$this->imageToDeleteIndex]);
            unset($this->storedImages[$this->imageToDeleteIndex]);
            $this->storedImages = array_values($this->storedImages); // Reindex array
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteIndex = null;

        session()->flash('message', 'Image successfully deleted.');
    }


    public function render()
    {
        return view('livewire.admin.event-halls.edit-event-hall');
    }
}
