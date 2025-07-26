<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use App\Models\Property;
use App\Models\PropertyFeature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app')]
class EditEventHall extends Component
{
    use WithFileUploads;

    public Property $eventHall;
    public $property_type_id = 3;

    public $name_number;
    public $description;
    public $amount;
    public $capacity;
    public $extra_charge_per_hour;
    public $image;
    public $newImage;
    public $newImages = [];
    public $storedImages = [];
    public $displayImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteId = null;
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
        //Only mount active inclusions
        $this->inclusions = PropertyFeature::where('property_type_id', 3)
        ->where('is_active', true)
        ->get();

        $this->eventHall = $eventHall;
        $this->eventHallId = $eventHall->id;
        $this->name_number = $eventHall->name_number;
        $this->description = $eventHall->description;
        $this->amount = $eventHall->amount;
        $this->capacity = $eventHall->capacity;
        $this->extra_charge_per_hour = $eventHall->extra_charge_per_hour;
        $this->property_status = $eventHall->property_status;
        $this->image = $eventHall->image;

        // initialize storedImages with unique IDs for sorting/removal
        $this->storedImages = collect($eventHall->images ?? [])->map(function ($path) {
            return ['id' => Str::random(10), 'path' => $path]; // Assign a unique ID and store the path
        })->toArray();

        // Initial combined display images
        $this->updateDisplayImages();

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

        // Handle image upload if a new one is selected
        $finalImagePaths = [];
        foreach ($this->displayImages as $imageItem) {
            if (isset($imageItem['path'])) {
                // uploaded image
                $finalImagePaths[] = $imageItem['path'];
            } elseif (isset($imageItem['object'])) {
                // new temp file so store
                $path = $imageItem['object']->store('event-halls', 'public');
                $finalImagePaths[] = $path;
            }
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
            'images' => $finalImagePaths,
        ]);

        $this->eventHall->features()->sync($this->selectedFeatures);

        session()->flash('message', 'Event Hall successfully updated!');
        $this->confirmEditItem = false;
        return redirect()->route('admin.event-halls');
    }

    public function updatedNewImages()
    {
        $this->updateDisplayImages();
    }

    protected function updateDisplayImages()
    {
        // Extract current new (temporary) images from displayImages to keep them
        $existingTempImages = collect($this->displayImages)->filter(function ($image) {
            return !in_array($image, $this->storedImages);
        });

        // Map new uploaded images with unique IDs
        $newImagePreviewsWithIds = collect($this->newImages)->map(function ($image) {
            return ['id' => $image->getFilename(), 'object' => $image];
        });

        // Merge stored images, existing temp images, and new uploads
        $this->displayImages = array_merge($this->storedImages, $existingTempImages->toArray(), $newImagePreviewsWithIds->toArray());

        // Clear newImages to reset input
        $this->newImages = [];
    }

    public function confirmImageDelete($id)
    {
        $this->imageToDeleteId = $id;
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
    {
        // find image by id
        $indexToRemove = null;
        foreach ($this->displayImages as $key => $image) {
            if ($image['id'] === $this->imageToDeleteId) {
                $indexToRemove = $key;
                break;
            }
        }

        if (is_numeric($indexToRemove)) {
            $imageToRemove = $this->displayImages[$indexToRemove];

            // if stored image, delete from storage
            if (isset($imageToRemove['path'])) {
                Storage::disk('public')->delete($imageToRemove['path']);
                // and remove from the storedImages array
                $this->storedImages = collect($this->storedImages)->filter(function($img) use ($imageToRemove) {
                    return $img['id'] !== $imageToRemove['id'];
                })->values()->toArray();
            }
            // if new upload, temp object lang,
            // remove from newImages if it's there
            elseif (isset($imageToRemove['object'])) {
                $this->newImages = collect($this->newImages)->filter(function($img) use ($imageToRemove) {
                    return $img->getFilename() !== $imageToRemove['id'];
                })->values()->toArray();
            }

            $this->updateDisplayImages(); // Re-update display array after removal
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteId = null;

        session()->flash('message', 'Image successfully deleted.');
    }


    public function render()
    {
        return view('livewire.admin.event-halls.edit-event-hall');
    }
}
