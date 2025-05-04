<?php

namespace App\Livewire\Admin\Properties;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;

class CreateProperty extends Component
{

    use WithFileUploads;

    public $property_type_id = 2; // Property Type = House

    // ----------------------------- House Details ---------------------------------------//


    public $name_number;
    public $capacity;
    public $max_adults;
    public $max_kids;
    public $image;
    public $description;
    public $amount; // Monthly Rent
    public $property_status = 'available';

    // ----------------------------- House Address Details ------------------------------//
    public $house_number;
    public $street;
    public $barangay;
    public $city_municipality;
    public $region;
    public $postal_code;
    public $country;

    // ----------------------- House Features (Amenities) -------------------------------//
    public $selectedFeatures = [];
    public $features = [];

    // ----------------------------- Modals ---------------------------------------------//
    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        //only mount house features
        $this->features = PropertyFeature::where('property_type_id', 2)->get();
    }


    public function saveProperty()
    {
        try {
            // Validate form input 
            $this->validate([
                'name_number' => 'required|string|max:255|unique:properties,name_number',
                'property_type_id' => 'required|exists:property_types,id',
                'capacity' => 'required|integer|min:1',
                'max_adults' => 'required|integer|min:1',
                'max_kids' => 'required|integer|min:0',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:1000000.00',
                'image' => 'nullable|image|max:1024',
                'description' => 'nullable|string',
                'house_number' => 'required|string',
                'street' => 'required|string',
                'barangay' => 'required|string',
                'city_municipality' => 'required|string',
                'region' => 'required|string',
                'postal_code' => 'required|string',
                'country' => 'required|string',
                'selectedFeatures' => 'nullable|array',
                'selectedFeatures.*' => 'exists:property_features,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            $imagePath = $this->image->store('houses', 'public');
        }

        // Create Property
        $house = Property::create([
            'property_type_id' => $this->property_type_id,
            'name_number' => $this->name_number,
            'capacity' => $this->capacity,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'amount' => $this->amount,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'property_status' => $this->property_status,
            'description' => $this->description,
            'image' => $imagePath, // Save path in DB
        ]);

        // Attach selected features to pivot
        if (!empty($this->features)) {
            $house->features()->attach($this->features);
        }

        // Reset form fields
        $this->reset([
            'name_number',
            'capacity',
            'max_adults',
            'max_kids',
            'amount',
            'house_number',
            'street',
            'barangay',
            'city_municipality',
            'postal_code',
            'country',
            'property_status',
            'description',
            'image',
            'selectedFeatures',
        ]);

        // Flash message for success
        session()->flash('message', 'Property successfully created!');

        // Redirect back to property list
        return redirect()->route('admin.properties');
    }

    public function render()
    {
        return view('livewire.admin.properties.create-property');
    }
}








// public $property_category_id;

// public $houseCategories;

// $this->houseCategories = PropertyCategory::all();   // Load categories
// 'property_category_id' => 'required|exists:property_categories,id',
// 'property_category_id',
// 'property_category_id' => $this->property_category_id,
