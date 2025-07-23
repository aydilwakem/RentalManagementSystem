<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Barangay;
use App\Models\Municipality;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\Province;
use App\Models\Region;

class CreateProperty extends Component
{
    use WithFileUploads;

    public $property_type_id = 2; // Property Type = House
    public $property_category_id;

    // ----------------------------- House Details ---------------------------------------//

    public $name_number;
    public $capacity;
    public $max_adults;
    public $max_kids;
    public $image;
    public $images = [];
    public $storedImages = [];
    public $description;
    public $amount; // Monthly Rent
    public $property_status = 'available';

    // ----------------------------- House Address Details ------------------------------//
    public $house_number;
    public $street;
    public $barangay;
    public $city_municipality;
    public $province;
    public $region;
    public $postal_code;
    public $country;

    //------------------------------ Address Mounting -------------------------------//
    public $provinces = [];
    public $municipalities = [];
    public $barangays = [];
    public $regions = [];
    
    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedMunicipality = null; 
    public $selectedBarangay = null;


    // ----------------------- House Features (Amenities) -------------------------------//
    public $selectedFeatures = [];
    public $features = [];

    // ----------------------------- Modals ---------------------------------------------//
    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    // ----------------------------- Mount --------------------------------------- //

    public function mount()
    {
        //only mount active house features
        $this->features = PropertyFeature::where('property_type_id', 2)
        ->where('is_active', true)
        ->get();

        //Address Mounting
        $this->regions = Region::orderBy('PSGC_REG_DESC')->get();
    }

    // ----------------------------- Address Selectors --------------------------------------- //
    public function updatedSelectedRegion($regionCode)
    {
        $this->provinces = Province::where('PSGC_REG_CODE', $regionCode)->orderBy('PSGC_PROV_DESC')->get();
        $this->selectedProvince = null;
        $this->municipalities = [];
        $this->barangays = [];
    }

    public function updatedSelectedProvince($provinceCode)
    {
        $this->municipalities = Municipality::where('PSGC_PROV_CODE', $provinceCode)->orderBy('PSGC_MUNC_DESC')->get();
        $this->selectedMunicipality = null;
        $this->barangays = [];
    }

    public function updatedSelectedMunicipality($municipalityCode)
    {
        $this->barangays = Barangay::where('PSGC_MUNC_CODE', $municipalityCode)->orderBy('PSGC_BRGY_DESC')->get();
        $this->selectedBarangay = null;
    }

    public function updatedSelectedBarangay($barangayCode)
    {
        $barangay = Barangay::where('PSGC_BRGY_CODE', $barangayCode)->first();

        if ($barangay && $barangay->PSGC_ZIP_CODE) {
            $this->postal_code = $barangay->PSGC_ZIP_CODE;
        } else {
            $this->postal_code = null;
        }
    }

    // ------------------------------ Image Removal -------------------------------- //
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

    public function saveProperty()
    {
        try {
            // Validate form input
            $this->validate([
                'name_number' => 'required|string|max:100|unique:properties,name_number',
                'property_type_id' => 'required|exists:property_types,id',
                // 'capacity' => 'required|integer|min:1',
                // 'max_adults' => 'required|integer|min:1',
                // 'max_kids' => 'required|integer|min:0',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:100000.00',
                'image' => 'nullable|image|max:2024',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
                'description' => 'nullable|string',
                'house_number' => 'required|string',
                'street' => 'required|string',
                'selectedBarangay' => 'required|string',
                'selectedMunicipality' => 'required|string',
                'selectedRegion' => 'required|string',
                'selectedProvince' => 'required|string',
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

        $imagePaths = [];
        if (is_array($this->images)) {
            foreach ($this->images as $image) {
                if ($image->isValid()) {
                    $path = $image->store('houses', 'public');
                    $imagePaths[] = $path;
                } else {
                    session()->flash('error', 'Image upload failed. Please try again.');
                    return;
                }
            }
        }

        // Create Property
        $house = Property::create([
            'property_type_id' => $this->property_type_id,
            'name_number' => $this->name_number,
            // 'capacity' => $this->capacity,
            // 'max_adults' => $this->max_adults,
            // 'max_kids' => $this->max_kids,
            'amount' => $this->amount,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'barangay' => $this->selectedBarangay,
            'city_municipality' => $this->selectedMunicipality,
            'region' => $this->selectedRegion,
            'province' => $this->selectedProvince,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'property_status' => $this->property_status,
            'description' => $this->description,
            'image' => $imagePath, // Save path in DB
            'images' => array_merge($imagePaths, $this->storedImages),

        ]);

        // Attach selected features to pivot
        if (!empty($this->selectedFeatures)) {
            $house->features()->attach($this->selectedFeatures);
        }

        // Reset form fields
        $this->reset([
        'name_number',
        // 'capacity',
        // 'max_adults',
        // 'max_kids',
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
        'selectedFeatures']);

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
