<?php

namespace App\Livewire\Admin\HouseCategories;

use App\Models\HouseCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditHouseCategory extends Component
{
    use WithFileUploads;

    public HouseCategory $houseCategory;
    public $name;
    public $description;
    public $houseCategoryId;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(HouseCategory $houseCategory)
    {
        $this->houseCategory = $houseCategory;
        $this->houseCategoryId = $houseCategory->id;
        $this->name = $houseCategory->name;
        $this->description = $houseCategory->description;
    }

    public function updateHouseCategory()
    {
        try {
            $this->validate([
                'name' => "required|string|max:255|unique:lt_house_categories,name,{$this->houseCategoryId},id",
                'description' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Update House Category
        $this->houseCategory->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 'House Category successfully updated!');

        return redirect()->route('admin.house-categories');
    }

    public function render()
    {
        return view('livewire.admin.house-categories.edit-house-category');
    }
}
