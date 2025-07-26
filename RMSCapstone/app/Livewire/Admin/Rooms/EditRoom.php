<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class EditRoom extends Component
{
    use WithFileUploads;

    public Property $room;
    public $property_type_id = 1;

    public $name_number;
    public $property_category_id;
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $max_guests;
    public $occupancy_type;
    public $occupancy_rules = [];
    public $turnover_duration;
    public $property_status;
    public $amount;
    public $extra_person_charge;
    public $image;
    public $newImage;
    public $newImages = [];
    public $storedImages = [];
    public $displayImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteId = null;
    public $features; // All available features
    public $selectedFeatures = []; // Selected feature IDs
    public $roomCategories; // Store room categories for dropdown
    public $roomId;
    public $amenities;
    public $freebies = false;

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(Property $room)
    {
        //only mount amenities for Room
        $this->amenities = PropertyFeature::where('property_type_id', 1)->where('is_active', true)->get();

        $this->roomId = $room->id;
        $this->room = $room;
        $this->name_number = $room->name_number;
        $this->property_category_id = $room->property_category_id;
        $this->ideal_guest = $room->ideal_guest;
        $this->max_adults = $room->max_adults;
        $this->max_kids = $room->max_kids;
        $this->turnover_duration = $room->turnover_duration;
        $this->property_status = $room->property_status;
        $this->amount = $room->amount;
        $this->image = $room->image;
        $this->roomCategories = PropertyCategory::all();
        $this->extra_person_charge = $room->extra_person_charge;
        $this->features = PropertyFeature::all();
        $this->occupancy_type = $room->occupancy_type;

        $this->selectedFeatures = $room->features()->pluck('property_features.id')->toArray();
        $this->freebies = (bool) $room->freebies;

        // initialize storedImages with unique IDs for sorting/removal
        $this->storedImages = collect($room->images ?? [])
            ->map(function ($path) {
                return ['id' => Str::random(10), 'path' => $path]; // Assign a unique ID and store the path
            })
            ->toArray();

        // Initial combined display images
        $this->updateDisplayImages();
    }

    public function addRule()
    {
        $this->occupancy_rules[] = ['adults' => 2, 'kids' => 2]; // Default rule
    }

    public function removeRule($index)
    {
        unset($this->occupancy_rules[$index]);
        $this->occupancy_rules = array_values($this->occupancy_rules); // Re-index array
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
                $this->storedImages = collect($this->storedImages)
                    ->filter(function ($img) use ($imageToRemove) {
                        return $img['id'] !== $imageToRemove['id'];
                    })
                    ->values()
                    ->toArray();
            }
            // if new upload, temp object lang,
            // remove from newImages if it's there
            elseif (isset($imageToRemove['object'])) {
                $this->newImages = collect($this->newImages)
                    ->filter(function ($img) use ($imageToRemove) {
                        return $img->getFilename() !== $imageToRemove['id'];
                    })
                    ->values()
                    ->toArray();
            }

            $this->updateDisplayImages(); // Re-update display array after removal
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteId = null;

        session()->flash('message', 'Image successfully deleted.');
    }

    public function updateRoom()
    {
        try {
            $this->validate([
                'name_number' => "required|string|max:100|unique:properties,name_number,{$this->roomId},id",
                'property_category_id' => 'nullable|exists:property_categories,id',
                'ideal_guest' => [
                    'required',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $totalCapacity = $this->max_adults + $this->max_kids;
                        if ($value > $totalCapacity) {
                            $fail('Ideal guest must not exceed the sum of maximum adults and maximum kids.');
                        }
                    },
                ],
                'max_adults' => 'required|integer|min:1|max:20',
                'max_kids' => 'required|integer|min:0|max:10',
                'turnover_duration' => 'required|integer|min:1',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:20000.00',
                'extra_person_charge' => 'required|numeric|min:100|max:100000.00',
                'newImage' => 'nullable|image|max:2048',
                'newImages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'occupancy_rules' => 'required|array',
                'occupancy_rules.*.adults' => 'required|integer|min:0',
                'occupancy_rules.*.kids' => 'required|integer|min:0',
                'freebies' => 'nullable|boolean',
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
                $path = $imageItem['object']->store('rooms', 'public');
                $finalImagePaths[] = $path;
            }
        }

        // Update room details
        $this->room->update([
            'name_number' => $this->name_number,
            'property_category_id' => $this->property_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'turnover_duration' => $this->turnover_duration,
            'property_status' => $this->property_status,
            'amount' => $this->amount,
            'extra_person_charge' => $this->extra_person_charge,
            'image' => $this->image,
            'images' => $finalImagePaths,
            'occupancy_rules' => $this->occupancy_rules,
            'occupancy_type' => $this->occupancy_type,
            'freebies' => (bool) $this->freebies,
        ]);

        $this->room->features()->sync($this->selectedFeatures);

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
