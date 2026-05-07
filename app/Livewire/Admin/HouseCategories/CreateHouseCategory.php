<?php

namespace App\Livewire\Admin\HouseCategories;

use App\Models\HouseCategory;
use Livewire\Component;

class CreateHouseCategory extends Component
{
    public $name;
    public $description;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function saveHouseCategory()
    {
        try {
            // Validate form input
            $this->validate([
                'name' => 'required|string|max:255|unique:lt_house_categories,name',
                'description' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create House Category
        HouseCategory::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Reset form fields
        $this->reset('name', 'description');

        // Flash message for success
        session()->flash('message', 'House category successfully created!');

        // Redirect back to house categories list
        return redirect()->route('admin.house-categories');
    }

    public function render()
    {
        return view('livewire.admin.house-categories.create-house-category');
    }
}
