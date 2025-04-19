<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\HouseCategory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditProperty extends Component
{
    use WithFileUploads;

    public Property $property;
    public $name;
    public $description;
    public $monthly_rent;
    public $availability;
    public $house_number;
    public $street;
    public $barangay;
    public $city_municipality;
    public $province;
    public $region;
    public $postal_code;
    public $country;
    public $houseCategories;
    public $house_category_id;
    public $propertyId;
    public $image;
    public $newImage;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    public function mount(Property $property)
    {

        dd($property);
        $this->propertyId = $property->id;
        $this->houseCategories = HouseCategory::all();
        $this->property = $property;
        $this->image = $property->image;
        $this->fill($property->toArray());
    }

    public function updateProperty()
    {
        try {
            // Validate form input 
            $this->validate([
                'name' => "required|string|unique:lt_houses,name,{$this->propertyId},id",
                'house_category_id' => 'required|exists:lt_house_categories,id',
                'description' => 'nullable|string',
                'monthly_rent' => 'required|numeric|min:0',
                'availability' => 'required|in:available,unavailable',
                'house_number' => 'required|string',
                'street' => 'required|string',
                'barangay' => 'required|string',
                'city_municipality' => 'required|string',
                'province' => 'required|string',
                'region' => 'required|string',
                'postal_code' => 'required|string',
                'country' => 'required|string',
                'newImage' => 'nullable|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Ensure the image is uploaded properly
        if ($this->newImage && !$this->newImage->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Handle Image Upload
        if ($this->newImage) {
            if ($this->property->image) {
                Storage::disk('public')->delete($this->property->image);
            }
            // Save the image in public folder
            $this->image = $this->newImage->store('houses', 'public');
        }

        $this->property->update([
            'name' => $this->name,
            'house_category_id' => $this->house_category_id,
            'description' => $this->description,
            'monthly_rent' => $this->monthly_rent,
            'availability' => $this->availability,
            'house_number' => $this->house_number,
            'street' => $this->street,
            'barangay' => $this->barangay,
            'city_municipality' => $this->city_municipality,
            'province' => $this->province,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'image' => $this->image,
        ]);

        session()->flash('message', 'Property successfully updated!');
        return redirect()->route('admin.properties');
    }

    public function render()
    {
        return view('livewire.admin.properties.edit-property', [
            'houseCategories' => $this->houseCategories,
        ]);
    }
}
