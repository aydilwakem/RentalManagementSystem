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
use Illuminate\Validation\Rule;
use Illuminate\Validation\Logs;

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

    #[Rule(['images.*' => 'image|max:2024'])]
    public $uploadedImagePreviews = []; // temporary url for preview
    public $persistedImagePaths = []; // string paths once uploaded
    protected $listeners = ['updateImageOrder'];
    public array $originalCombinations = [];

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
        // $this->max_guests = $room->max_guests;
        $this->occupancy_type = $room->occupancy_type;
        $this->roomCategories = PropertyCategory::all();
        // $this->occupancy_rules = $room->occupancy_rules ?? [];
        $this->extra_person_charge = $room->extra_person_charge;
        $this->features = PropertyFeature::all();
        $this->occupancy_type = $room->occupancy_type;

        $this->selectedFeatures = $room->features()->pluck('property_features.id')->toArray();
        $this->freebies = (bool) $room->freebies;

        $this->room = $room;

        $this->occupancy_type = $room->occupancy_type;

        // CASE 1: combinations
        if ($room->occupancy_type === 'combinations') {
            $this->occupancy_rules = $room->occupancy_rules ?? [];
            $this->originalCombinations = collect($this->occupancy_rules)->where('type', 'original')->values()->toArray();
        }

        // CASE 2: whole_number
        if ($room->occupancy_type === 'whole_number') {
            $this->max_guests = $room->max_guests;
        }

        // initialize storedImages with unique IDs for sorting/removal
        $this->storedImages = collect($room->images ?? [])
            ->map(function ($path) {
                return ['id' => Str::random(10), 'path' => $path]; // Assign a unique ID and store the path
            })
            ->toArray();

        // Initial combined display images
        $this->updateDisplayImages();
    }

    public function updatedOccupancyType($value)
    {
        if ($value === 'combinations') {
            if (empty($this->originalCombinations)) {
                $this->originalCombinations = [['adults' => 1, 'kids' => 0, 'type' => 'original']];
            }

            // Convert to occupancy_rules for combinations
            $this->occupancy_rules = $this->originalCombinations;
        }

        if ($value === 'whole_number') {
            $this->originalCombinations = [];
            $this->occupancy_rules = [];

            $this->max_guests = $this->max_guests ?? null;
        }
    }

    public function addRule()
    {
        $newRule = ['adults' => 2, 'kids' => 0, 'type' => 'original'];
        $this->originalCombinations[] = $newRule;
        $this->occupancy_rules = $this->originalCombinations;
    }
    public function removeRule($index)
    {
        unset($this->originalCombinations[$index]);
        $this->originalCombinations = array_values($this->originalCombinations);
        $this->occupancy_rules = $this->originalCombinations;
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

            $this->displayImages = collect($this->displayImages)
            ->filter(fn($img) => $img['id'] !== $this->imageToDeleteId)
            ->values()
            ->toArray();

            $this->updateDisplayImages(); // Re-update display array after removal
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteId = null;

        session()->flash('message', 'Image successfully deleted.');
    }

    public function updateRoom()
    {
        $this->validate();

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

        // If combinations, generate subcombinations
        $finalOccupancy = null;
        if ($this->occupancy_type === 'combinations') {
            $finalOccupancy = $this->generateCombinations();
        }

        // Update room details
        $this->room->update([
            'name_number' => $this->name_number,
            'property_category_id' => $this->property_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'max_guests' => $this->max_guests,
            'occupancy_type' => $this->occupancy_type,
            'turnover_duration' => $this->turnover_duration,
            'property_status' => $this->property_status,
            'amount' => $this->amount,
            'extra_person_charge' => $this->extra_person_charge,
            'image' => $this->image,
            'images' => $finalImagePaths,
            'freebies' => (bool) $this->freebies,
            'occupancy_rules' => $this->occupancy_type === 'combinations' ? $finalOccupancy : null,
            'max_guests' => $this->occupancy_type === 'whole_number' ? $this->max_guests : null,
        ]);

        $this->room->features()->sync($this->selectedFeatures);

        session()->flash('message', 'Room successfully updated!');

        return redirect()->route('admin.rooms');
    }

    protected function rules()
    {
        $baseRules = [
            'name_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('properties', 'name_number')
                    ->ignore($this->roomId)
                    ->where(function ($query) {
                        return $query->where('property_type_id', $this->property_type_id)->whereNull('deleted_at');
                    }),
            ],
            'property_category_id' => 'required|exists:property_categories,id',
            'property_type_id' => 'required|exists:property_types,id',
            'ideal_guest' => 'required|integer|min:1|max:20',
            'occupancy_type' => 'required|in:combinations,whole_number,ideal_guest',
            'turnover_duration' => 'required|min:1|max:20',
            'property_status' => 'required|in:available,booked,out_of_service',
            'amount' => 'required|numeric|min:100|max:20000.00',
            'extra_person_charge' => 'required|numeric|min:100|max:10000.00',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2024',
            'newImages' => 'nullable|array',
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
            'persistedImagePaths' => 'nullable|array',
            'selectedFeatures' => 'nullable|array',
            'selectedFeatures.*' => 'exists:property_features,id',
            'freebies' => 'nullable|boolean',
        ];

        if ($this->occupancy_type === 'combinations') {
            $baseRules['occupancy_rules'] = 'required|array|min:1';
            $baseRules['occupancy_rules.*.adults'] = 'required|integer|min:0';
            $baseRules['occupancy_rules.*.kids'] = 'required|integer|min:0';
        }

        if ($this->occupancy_type === 'whole_number') {
            $baseRules['max_guests'] = 'required|integer|min:1|max:30';
        }

        return $baseRules;
    }

    public function updatedOriginalCombinations()
    {
        $this->occupancy_rules = $this->originalCombinations;
    }

    public function generateCombinations()
    {
        $rules = $this->occupancy_rules;
        $combinations = [];

        // Avoid duplicate combinations
        $seen = [];

        foreach ($rules as $rule) {
            $adults = (int) $rule['adults'];
            $kids = (int) $rule['kids'];

            // Original
            $comboKey = "{$adults}-{$kids}";
            if (!isset($seen[$comboKey])) {
                $combinations[] = [
                    'adults' => $adults,
                    'kids' => $kids,
                    'type' => 'original',
                ];
                $seen[$comboKey] = true;
            }

            // Subcombinations
            for ($a = 1; $a <= $adults; $a++) {
                for ($k = 0; $k <= $kids; $k++) {
                    if ($a === $adults && $k === $kids) {
                        continue;
                    }

                    $key = "{$a}-{$k}";
                    if (!isset($seen[$key])) {
                        $combinations[] = [
                            'adults' => $a,
                            'kids' => $k,
                            'type' => 'sub',
                        ];
                        $seen[$key] = true;
                    }
                }
            }
        }

        // Sort: original first, then by adults, then kids
        usort($combinations, function ($a, $b) {
            if ($a['type'] === $b['type']) {
                return $a['adults'] === $b['adults'] ? $a['kids'] <=> $b['kids'] : $a['adults'] <=> $b['adults'];
            }
            return $a['type'] === 'original' ? -1 : 1;
        });

        return $combinations;
    }

    public function render()
    {
        return view('livewire.admin.rooms.edit-room', [
            'roomCategories' => $this->roomCategories,
        ]);
    }
}
