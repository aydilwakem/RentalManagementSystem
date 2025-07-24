<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use Illuminate\Validation\Rule;

class CreateRoom extends Component
{
    use WithFileUploads;

    public $name_number;
    public $property_category_id;
    public $property_type_id = 1; // Room
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $occupancy_rules = [['adults' => 2, 'kids' => 2], ['adults' => 3, 'kids' => 0]]; // Default values for occupancy rules
    public $turnover_duration;
    public $property_status = 'available'; // Default
    public $amount;

    #[Rule(['images.*' => 'image|max:2024'])]
    public $newImages = []; //new files
    public $uploadedImagePreviews = []; // temporary url for preview
    public $persistedImagePaths = []; // string paths once uploaded
    protected $listeners = ['updateImageOrder'];
    public $image;
    public $extra_person_charge;
    public $selectedFeatures = []; // Selected feature IDs
    public $features = []; // All features
    public $roomCategories; // All room categories
    public $confirmCreateItem = false;
    public $freebies = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        $this->roomCategories = PropertyCategory::all(); // Load categories

        //mount only active room inclusions
        $this->features = PropertyFeature::where('property_type_id', 1)
            ->where('is_active', true)
            ->get();
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
        } else if (isset($this->persistedImagePaths[$index])) {
            unset($this->persistedImagePaths[$index]);
            $this->persistedImagePaths = array_values($this->persistedImagePaths);
        }
    }

    public function reorderImages($order)
    {
        // Combine all images
        $allImagesForReorder = array_merge($this->uploadedImagePreviews, $this->persistedImagePaths);
        $reordered = collect($order)->map(function ($index) use ($allImagesForReorder) {
            return $allImagesForReorder[$index];
        })->values()->toArray();

        $this->uploadedImagePreviews = []; // Clear temporary ones
        $this->persistedImagePaths = []; // Clear persisted ones

        foreach ($reordered as $item) {
            if (is_object($item) && method_exists($item, 'temporaryUrl')) {
                $this->uploadedImagePreviews[] = $item;
            } else {
                $this->persistedImagePaths[] = $item;
            }
        }
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

    public function saveRoom()
    {
        try {
            $this->validate([
                'name_number' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('properties', 'name_number')
                        ->where(function ($query) {
                            return $query->where('property_type_id', $this->property_type_id)
                                ->whereNull('deleted_at'); // Ignore soft-deleted properties
                        }),
                ],
                'property_category_id' => 'required|exists:property_categories,id',
                'property_type_id' => 'required|exists:property_types,id',
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
                'turnover_duration' => 'required|string',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:20000.00',
                'extra_person_charge' => 'required|numeric|min:100|max:10000.00',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2024',
                'newImages' => 'nullable|array',
                'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
                'persistedImagePaths' => 'nullable|array',
                'selectedFeatures' => 'nullable|array',
                'selectedFeatures.*' => 'exists:property_features,id',
                'occupancy_rules' => 'required|array',
                'occupancy_rules.*.adults' => 'required|integer|min:0',
                'occupancy_rules.*.kids' => 'required|integer|min:0',
                'freebies' => 'nullable|boolean'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        $allStoredImagePaths = [];

        // store newly uploaded images
        foreach ($this->uploadedImagePreviews as $imageObject) {
            if (is_object($imageObject) && method_exists($imageObject, 'isValid')) {
                if ($imageObject->isValid()) {
                    $path = $imageObject->store('rooms', 'public');
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
            $mainImagePath = $this->image->store('rooms', 'public');
        }


        // Create the room
        $room = Property::create([
            'name_number' => $this->name_number,
            'property_type_id' => $this->property_type_id,
            'property_category_id' => $this->property_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'turnover_duration' => $this->turnover_duration,
            'property_status' => $this->property_status,
            'extra_person_charge' => $this->extra_person_charge,
            'amount' => $this->amount,
            'image' => $mainImagePath,
            'images' => $allStoredImagePaths,
            'occupancy_rules' => $this->occupancy_rules,
            'freebies' => (bool) $this->freebies,
        ]);

        // Attach selected features to pivot
        if (!empty($this->selectedFeatures)) {
            $room->features()->attach($this->selectedFeatures);
        }

        // Reset the form
        $this->reset([
            'name_number',
            'property_category_id',
            'ideal_guest',
            'max_adults',
            'max_kids',
            'turnover_duration',
            'property_status',
            'amount',
            'extra_person_charge',
            'image',
            'newImages',
            'uploadedImagePreviews',
            'persistedImagePaths',
            'selectedFeatures',
            'occupancy_rules',
            'freebies'
        ]);

        session()->flash('message', 'Room successfully created!');
        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.rooms.create-room', [
            'roomCategories' => $this->roomCategories,
            'features' => $this->features,
        ]);
    }
}
