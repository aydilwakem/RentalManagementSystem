<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
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
    public $images = [];
    public $storedImages = [];
    public $image; // Single image
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

    public function removeRule($index)
    {
        unset($this->occupancy_rules[$index]);
        $this->occupancy_rules = array_values($this->occupancy_rules); // Re-index array
    }

    public function saveRoom()
    {
        $this->validate();

        // Image logic
        $imagePath = null;
        if ($this->image && $this->image->isValid()) {
            $imagePath = $this->image->store('rooms', 'public');
        }

        $imagePaths = [];
        if (is_array($this->images)) {
            foreach ($this->images as $image) {
                if ($image->isValid()) {
                    $path = $image->store('rooms', 'public');
                    $imagePaths[] = $path;
                }
            }
        }


        // If combinations, generate subcombinations
        $finalOccupancy = null;
        if ($this->occupancy_type === 'combinations') {
            $finalOccupancy = $this->generateCombinations();
        }

        // Save the property
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
            'image' => $imagePath,
            'images' => array_merge($imagePaths, $this->storedImages),
            'occupancy_rules' => $finalOccupancy,
            'freebies' => (bool) $this->freebies,
        ]);

        if (!empty($this->selectedFeatures)) {
            $room->features()->attach($this->selectedFeatures);
        }

        $this->reset([
            'name_number',
            'property_category_id',
            'ideal_guest',
            'max_adults',
            'max_kids',
            'max_guests',
            'occupancy_type',
            'turnover_duration',
            'property_status',
            'amount',
            'extra_person_charge',
            'image',
            'images',
            'selectedFeatures',
            'occupancy_rules',
            'freebies'
        ]);

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
                Rule::unique('properties', 'name_number')
                    ->where(function ($query) {
                        return $query->where('property_type_id', $this->property_type_id)
                            ->whereNull('deleted_at');
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
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
                    'type' => 'original'
                ];
                $seen[$comboKey] = true;
            }

            // Subcombinations
            for ($a = 1; $a <= $adults; $a++) {
                for ($k = 0; $k <= $kids; $k++) {
                    if ($a === $adults && $k === $kids) continue;

                    $key = "{$a}-{$k}";
                    if (!isset($seen[$key])) {
                        $combinations[] = [
                            'adults' => $a,
                            'kids' => $k,
                            'type' => 'sub'
                        ];
                        $seen[$key] = true;
                    }
                }
            }
        }

        // Sort: original first, then by adults, then kids
        usort($combinations, function ($a, $b) {
            if ($a['type'] === $b['type']) {
                return $a['adults'] === $b['adults']
                    ? $a['kids'] <=> $b['kids']
                    : $a['adults'] <=> $b['adults'];
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
