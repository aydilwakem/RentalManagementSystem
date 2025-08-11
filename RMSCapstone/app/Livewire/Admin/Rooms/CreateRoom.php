<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyBed;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Logs;

class CreateRoom extends Component
{
    use WithFileUploads;

    public $name_number;
    public $property_category_id;
    public $property_type_id = 1; // Room
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $max_guests;
    public $occupancy_type;
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

    //For pivot beds
    public $bed_type = [];
    public $bed_quantity = [];

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        $this->roomCategories = PropertyCategory::all(); // Load categories

        //mount only active room inclusions
        $this->features = PropertyFeature::where('property_type_id', 1)->where('is_active', true)->get();
        
        $this->addBed();
    }

    // ---------------- For Bed Buttons ---------------- //
    public function addBed()
    {
        $this->bed_type[] = '';
        $this->bed_quantity[] = '';
    }

    public function removeBed($index)
    {
        unset($this->bed_type[$index]);
        unset($this->bed_quantity[$index]);

        // Reindex arrays to keep the indexes aligned
        $this->bed_type = array_values($this->bed_type);
        $this->bed_quantity = array_values($this->bed_quantity);
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

    public function reorderImages($order)
    {
        // Combine all images
        $allImagesForReorder = array_merge($this->uploadedImagePreviews, $this->persistedImagePaths);
        $reordered = collect($order)
            ->map(function ($index) use ($allImagesForReorder) {
                return $allImagesForReorder[$index];
            })
            ->values()
            ->toArray();

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

    public function removeRule($index)
    {
        unset($this->occupancy_rules[$index]);
        $this->occupancy_rules = array_values($this->occupancy_rules); // Re-index array
    }

    public function saveRoom()
    {
        try {
            $this->validate();
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

        // If combinations, generate subcombinations
        $finalOccupancy = null;
        if ($this->occupancy_type === 'combinations') {
            $finalOccupancy = $this->generateCombinations();
        }

        // Create the room
        $room = Property::create([
            'name_number' => $this->name_number,
            'property_type_id' => $this->property_type_id,
            'property_category_id' => $this->property_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'max_guests' => $this->max_guests,
            'occupancy_type' => $this->occupancy_type,
            'turnover_duration' => $this->turnover_duration,
            'property_status' => $this->property_status,
            'extra_person_charge' => $this->extra_person_charge,
            'amount' => $this->amount,
            'image' => $mainImagePath,
            'images' => $allStoredImagePaths,
            'occupancy_rules' => $finalOccupancy,
            'freebies' => (bool) $this->freebies,
        ]);

        //Table for beds
        foreach($this->bed_type as $i => $bedType){
            PropertyBed::create([
                'property_id' => $room->id, 
                'bed_type' => $bedType, 
                'bed_quantity' => $this->bed_quantity[$i], 
            ]); 
        }

       

        if (!empty($this->selectedFeatures)) {
            $room->features()->attach($this->selectedFeatures);
        }

        $this->reset(['name_number', 'property_category_id', 'ideal_guest', 'max_adults', 'max_kids', 'max_guests', 'occupancy_type', 'turnover_duration', 'property_status', 'amount', 'extra_person_charge', 'image', 'newImages', 'uploadedImagePreviews', 'persistedImagePaths', 'selectedFeatures', 'occupancy_rules', 'freebies']);

        session()->flash('message', 'Room successfully created!');
        return redirect()->route('admin.rooms');
    }

    protected function rules()
    {
        $baseRules = [
            'name_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('properties', 'name_number')->where(function ($query) {
                    return $query->where('property_type_id', $this->property_type_id)->whereNull('deleted_at');
                }),
            ],
            'property_category_id' => 'required|exists:property_categories,id',
            'property_type_id' => 'required|exists:property_types,id',
            'ideal_guest' => 'required|integer|min:1|max:20',
            'occupancy_type' => 'required|in:combinations,whole_number,ideal_guest',
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
            'freebies' => 'nullable|boolean',

            //For bed validation
            'bed_type.*' => 'required|in:single,double,queen,king,sofa_bed,single with pull-out',
            'bed_quantity.*' => 'required|integer|min:1',
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

    public function addRule()
    {
        $this->occupancy_rules[] = ['adults' => 2, 'kids' => 2]; // Default rule
    }

    // public function generateCombinations()
    // {
    //     $rules = $this->occupancy_rules;
    //     $combinations = [];

    //     foreach ($rules as $rule) {
    //         $adults = (int) $rule['adults'];
    //         $kids = (int) $rule['kids'];

    //         // Original combination
    //         $combinations[] = [
    //             'adults' => $adults,
    //             'kids' => $kids,
    //             'type' => 'original'
    //         ];

    //         // Generate subcombinations
    //         for ($a = 1; $a <= $adults; $a++) {
    //             for ($k = 0; $k <= $kids; $k++) {
    //                 if ($a == $adults && $k == $kids) continue;
    //                 $combinations[] = [
    //                     'adults' => $a,
    //                     'kids' => $k,
    //                     'type' => 'sub'
    //                 ];
    //             }
    //         }
    //     }

    //     return $combinations;
    // }

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
        return view('livewire.admin.rooms.create-room', [
            'roomCategories' => $this->roomCategories,
            'features' => $this->features,
        ]);
    }
}
