<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\HouseCategory;
use Livewire\Component;

class CreateProperty extends Component
{
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
    public $deleted_at;
    public $created_at;
    public $updated_at;

    public $houseCategory;
    public $house_category_id;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }



    public function mount()
    {
        $this->houseCategory = HouseCategory::all(); // Fetch all categories
    }


    public function saveProperty()
    {
        try {
            // Validate form input 
            $this->validate([
                'name' => 'required|string',
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
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create Property
        Property::create([
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
            'deleted_at' => $this->deleted_at,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Reset form fields
        $this->reset([
            'name',
            'house_category_id',
            'description',
            'monthly_rent',
            'availability',
            'house_number',
            'street',
            'barangay',
            'city_municipality',
            'province',
            'region',
            'postal_code',
            'country',
            'deleted_at',
            'created_at',
            'updated_at'
        ]);

        // Flash message for success
        session()->flash('message', 'Property successfully created!');

        // Redirect back to property list
        return redirect()->route('admin.properties');
    }

    public function render()
    {
        return view('livewire.admin.properties.create-property', [
            'houseCategories' => $this->houseCategory,
        ]);
    }
}
