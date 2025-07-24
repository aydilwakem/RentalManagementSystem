<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Barangay;
use App\Models\Municipality;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


#[Layout('layouts.app')]
class EditProperty extends Component
{
    use WithFileUploads;

    public Property $property;
    public $property_type_id = 2;

    // ----------------------------- House Details ---------------------------------------//

    public $name_number;
    public $capacity;
    public $max_adults;
    public $max_kids;
    public $image;
    public $newImage;
    public $newImages = [];
    public $storedImages = [];
    public $displayImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteId = null;
    public $description;
    public $amount; // Monthly Rent
    public $property_status;

    // ----------------------------- House Address Details ------------------------------//
    public $house_number;
    public $street;
    public $barangay;
    public $city_municipality;
    public $region;
    public $postal_code;
    public $country;

    // ----------------------------- Address Mounting -------------------------------//
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
    public $house_features;

    // ----------------------------- Modals ---------------------------------------------//
    public $confirmCreateItem = false;
    public $property_id;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    public function mount(Property $property)
    {
        //Only mount house features
        $this->house_features = PropertyFeature::where('property_type_id', 2)
        ->where('is_active', true)
        ->get();

        // House details
        $this->property_id = $property->id;
        $this->name_number = $property->name_number;
        // $this->capacity = $property->capacity;
        // $this->max_adults = $property->max_adults;
        // $this->max_kids = $property->max_kids;
        $this->amount = $property->amount;

        // House Address
        $this->house_number = $property->house_number;
        $this->street = $property->street;
        $this->selectedBarangay = $property->barangay;
        $this->selectedMunicipality = $property->city_municipality;
        $this->selectedProvince = $property->province;
        $this->selectedRegion = $property->region;
        $this->postal_code = $property->postal_code;
        $this->country = $property->country;

        // Other info
        $this->description = $property->description;
        $this->property_status = $property->property_status;
        $this->image = $property->image;
        $this->features = PropertyFeature::all();
        $this->selectedFeatures = $property->features()->pluck('property_features.id')->toArray();

        // Images
        // initialize storedImages with unique IDs for sorting/removal
        $this->storedImages = collect($property->images ?? [])->map(function ($path) {
            return ['id' => Str::random(10), 'path' => $path]; // Assign a unique ID and store the path
        })->toArray();
        // Initial combined display images
        $this->updateDisplayImages();

        //Address Mounting
        $this->regions = Region::orderBy('PSGC_REG_DESC')->get();
        $this->provinces = Province::where('PSGC_REG_CODE', $this->selectedRegion)->orderBy('PSGC_PROV_DESC')->get();
        $this->municipalities = Municipality::where('PSGC_PROV_CODE', $this->selectedProvince)->orderBy('PSGC_MUNC_DESC')->get();
        $this->barangays = Barangay::where('PSGC_MUNC_CODE', $this->selectedMunicipality)->orderBy('PSGC_BRGY_DESC')->get();
    }

    //------------------------------ Address Selectors --------------------------------------- //
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

    // ----------------------------- Image Removal ---------------------------------- //
    public function updatedNewImages()
    {
        $this->updateDisplayImages();
    }

    protected function updateDisplayImages()
    {
        // Map newImages to include unique IDs for temporary files
        $newImagePreviewsWithIds = collect($this->newImages)->map(function ($image) {
            return ['id' => $image->getFilename(), 'object' => $image]; // Use filename as ID for temp uploads
        })->toArray();

        // Combine stored and newly uploaded images for display.
        $this->displayImages = array_merge($this->storedImages, $newImagePreviewsWithIds);
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

    public function updateProperty()
    {
        try {
            $this->validate([
                'name_number' => "required|string|max:100|unique:properties,name_number,{$this->property_id},id",
                // 'capacity' => 'required|integer|min:1',
                // 'max_adults' => 'required|integer|min:1',
                // 'max_kids' => 'required|integer|min:0',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:100000.00',
                'house_number' => 'required|string',
                'street' => 'required|string',
                'selectedBarangay' => 'required|string',
                'selectedMunicipality' => 'required|string',
                'selectedRegion' => 'required|string',
                'selectedProvince' => 'required|string',
                'postal_code' => 'required|string',
                'country' => 'required|string',
                'description' => 'nullable|string',
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
                $path = $imageItem['object']->store('houses', 'public');
                $finalImagePaths[] = $path;
            }
        }


        // Update property details
        $this->property->update([
            'name_number' => $this->name_number,
            // 'capacity' => $this->capacity,
            // 'max_adults' => $this->max_adults,
            // 'max_kids' => $this->max_kids,
            'amount' => $this->amount,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'barangay' => $this->selectedBarangay,
            'city_municipality' => $this->selectedMunicipality,
            'province' => $this->selectedProvince,
            'region' => $this->selectedRegion,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'property_status' => $this->property_status,
            'description' => $this->description,
            'image' => $this->image,
            'images' => $finalImagePaths,
        ]);

        $this->property->features()->sync($this->selectedFeatures);

        session()->flash('message', 'House successfully updated!');

        return redirect()->route('admin.properties');
    }

    public function render()
    {
        return view('livewire.admin.properties.edit-property', data: []);
    }
}
