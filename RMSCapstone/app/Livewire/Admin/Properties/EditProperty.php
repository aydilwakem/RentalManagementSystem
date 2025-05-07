<?php

namespace App\Livewire\Admin\Properties;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyFeature;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditProperty extends Component
{
    use WithFileUploads;

    public Property $property;

    // ----------------------------- House Details ---------------------------------------//

    public $name_number;
    public $capacity;
    public $max_adults;
    public $max_kids;
    public $image;
    public $newImage;
    public $newImages = [];
    public $storedImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteIndex = null;
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

    // ----------------------- House Features (Amenities) -------------------------------//
    public $selectedFeatures = [];
    public $features = [];

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
        // House details
        $this->property_id = $property->id;
        $this->name_number = $property->name_number;
        $this->capacity = $property->capacity;
        $this->max_adults = $property->max_adults;
        $this->max_kids = $property->max_kids;
        $this->amount = $property->amount;

        // House Address
        $this->house_number = $property->house_number;
        $this->street = $property->street;
        $this->barangay = $property->barangay;
        $this->city_municipality = $property->city_municipality;
        $this->region = $property->region;
        $this->postal_code = $property->postal_code;
        $this->country = $property->country;

        // Other info
        $this->description = $property->description;
        $this->property_status = $property->property_status;
        $this->image = $property->image;
        $this->storedImages = $property->images ?? [];
        $this->features = PropertyFeature::all();
        $this->selectedFeatures = $property->features()->pluck('property_features.id')->toArray();
    }

    public function confirmImageDelete($index)
    {
        $this->imageToDeleteIndex = $index;
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
    {
        if (isset($this->storedImages[$this->imageToDeleteIndex])) {
            Storage::disk('public')->delete($this->storedImages[$this->imageToDeleteIndex]);
            unset($this->storedImages[$this->imageToDeleteIndex]);
            $this->storedImages = array_values($this->storedImages); // Reindex array
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteIndex = null;

        session()->flash('message', 'Image successfully deleted.');
    }

    public function updateProperty()
    {
        try {
            $this->validate([
                'name_number' => "required|string|max:255|unique:properties,name_number,{$this->property_id},id",
                'capacity' => 'required|integer|min:1',
                'max_adults' => 'required|integer|min:1',
                'max_kids' => 'required|integer|min:0',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:1000000.00',
                'house_number' => 'required|string',
                'street' => 'required|string',
                'barangay' => 'required|string',
                'city_municipality' => 'required|string',
                'region' => 'required|string',
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
         $newImagePaths = [];

         if (!empty($this->newImages)) {
             foreach ($this->newImages as $image) {
                 if ($image->isValid()) {
                     $path = $image->store('houses', 'public');
                     $newImagePaths[] = $path;
                 }
             }
         }

         // Merge old and new images
        $allImages = array_merge($this->storedImages, $newImagePaths);


        // Update property details
        $this->property->update([
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
            'image' => $this->image,
            'images' => $allImages,
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
