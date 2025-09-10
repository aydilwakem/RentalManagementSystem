<?php

namespace App\Livewire\Admin\EventHalls;

use App\Models\EventHall;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use Livewire\Attributes\Rule;
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
    public $images;
    public $newImages = []; //new files
    public $uploadedImagePreviews = []; // temporary url for preview
    public $persistedImagePaths = []; // string paths once uploaded
    protected $listeners = ['updateImageOrder'];
    public $property_status = 'available'; // Default
    public $property_category_id;
    public $property_type_id = 3; // Event Hall
    public $selectedFeatures = []; // Selected feature IDs
    public $features = []; // All features to show in UI
    public $eventCategories; // All event categories

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        //Mount only active event hall inclusions
        $this->features = PropertyFeature::where('property_type_id', 3)->where('is_active', true)->get();
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:2024|mimes:jpeg,png,jpg,gif',
        ]);

        foreach ($this->newImages as $image) {
            $this->uploadedImagePreviews[] = $image; // store temporary for prview
        }
        $this->newImages = [];
    }

    public function removeImage($index)
    {
        if (isset($this->uploadedImagePreviews[$index])) {
            unset($this->uploadedImagePreviews[$index]);
            $this->uploadedImagePreviews = array_values($this->uploadedImagePreviews);
        } elseif (isset($this->persistedImagePaths[$index])) {
            unset($this->persistedImagePaths[$index]);
            $this->persistedImagePaths = array_values($this->persistedImagePaths);
        }
    }

    public function saveEventHall()
    {
        try {
            // Validate form input (including image)
            $this->validate([
                'name_number' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/|unique:properties,name_number',
                'description' => 'nullable|string|regex:/^[A-Za-z\s\-]+$/',
                'amount' => 'required|numeric|min:10000|max:100000.00',
                'capacity' => 'required|numeric|min:20|max:200',
                'extra_charge_per_hour' => 'required|numeric|min:1000|max:50000.00',
                'property_status' => 'required|in:available,booked,out_of_service',
                'image' => 'nullable|image|max:1024', // Max 1MB image
                'newImages' => 'nullable|array',
                'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
                'persistedImagePaths' => 'nullable|array',
                'selectedFeatures' => 'nullable|array',
                'selectedFeatures.*' => 'exists:property_features,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        $allStoredImagePaths = [];

        // store newly uploaded images
        foreach ($this->uploadedImagePreviews as $imageObject) {
            if (is_object($imageObject) && method_exists($imageObject, 'isValid')) {
                if ($imageObject->isValid()) {
                    $path = $imageObject->store('event-halls', 'public');
                    $allStoredImagePaths[] = $path;
                } else {
                    session()->flash('error', 'Image upload failed. Please try again.');
                    return;
                }
            }
        }

        $allStoredImagePaths = array_merge($allStoredImagePaths, $this->persistedImagePaths);

        // single image
        $mainImagePath = null;
        if ($this->image && $this->image->isValid()) {
            $mainImagePath = $this->image->store('event-halls', 'public');
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
            'image' => $mainImagePath, // Save path in DB
            'images' => $allStoredImagePaths,
        ]);

        // Attach selected features to pivot
        if (!empty($this->features)) {
            $eventHall->features()->attach($this->selectedFeatures);
        }

        // Reset form fields
        $this->reset(['name_number', 'property_status', 'description', 'image', 'images', 'amount', 'capacity', 'extra_charge_per_hour', 'selectedFeatures']);

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
