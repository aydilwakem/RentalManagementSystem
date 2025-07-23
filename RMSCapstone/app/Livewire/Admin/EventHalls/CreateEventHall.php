<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateEventHall extends Component
{
    use WithFileUploads;

    public $name_number;
    public $description;
    public $amount;
    public $capacity;
    public $extra_charge_per_hour;
    public $image;
    public $images = [];
    public $storedImages = [];
    public $property_status = 'available'; // Default
    public $property_category_id;
    public $property_type_id = 3; // Room

    public $selectedFeatures = [];        // Selected feature IDs
    public $features = [];     // All features to show in UI
    public $eventCategories;       // All event categories


    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        //Mount only active event hall inclusions
        $this->features = PropertyFeature::where('property_type_id', 3)
        ->where('is_active', true)
        ->get();
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images); // reindex array
    }

    public function updatedImages()
    {
        // Prevent duplicate uploads by only appending new images
        $this->images = array_merge($this->storedImages, $this->images);
    }

    public function saveEventHall()
    {
        try{
        // Validate form input (including image)
        $this->validate([
            'name_number' => 'required|string|max:255|unique:properties,name_number',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:10000|max:100000.00',
            'capacity' => 'required|numeric|min:20|max:200',
            'extra_charge_per_hour' => 'required|numeric|min:1000|max:50000.00',
            'property_status' => 'required|in:available,booked,out_of_service',
            'image' => 'nullable|image|max:1024', // Max 1MB image
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
            'selectedFeatures' => 'nullable|array',
            'selectedFeatures.*' => 'exists:property_features,id',

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

        $imagePaths = [];
        if (is_array($this->images)) {
            foreach ($this->images as $image) {
                if ($image->isValid()) {
                    $path = $image->store('rooms', 'public');
                    $imagePaths[] = $path;
                } else {
                    session()->flash('error', 'Image upload failed. Please try again.');
                    return;
                }
            }
        }

        // Create Event Category
        $eventHall = Property::create([
            'name_number' => $this->name_number,
            'property_type_id' => $this->property_type_id,
            //'property_category_id' => $this->property_category_id,
            'description' => $this->description,
            'property_status' => $this->property_status,
            'amount' => $this->amount,
            'capacity' => $this->capacity,
            'extra_charge_per_hour' => $this->extra_charge_per_hour,
            'image' => $imagePath, // Save path in DB
            'images' => array_merge($imagePaths, $this->storedImages),
        ]);

        // Attach selected features to pivot
        if (!empty($this->features)) {
            $eventHall->features()->attach($this->selectedFeatures);
        }

        // Reset form fields
        $this->reset(['name_number',
        'property_status',
        'description',
        'image',
        'images',
        'amount',
        'capacity',
        'extra_charge_per_hour',
        'selectedFeatures',]);

        // Flash message for success
        session()->flash('message', 'Event Hall successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.event-halls');
    }


    public function render()
    {
        return view('livewire.admin.event-halls.create-event-hall', [
            'features' => $this->features,
        ]);
    }
}
