<?php

namespace App\Livewire\Admin\HouseCategories;

use App\Models\HouseCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewHouseCategory extends Component
{
    // Create a public property
    public HouseCategory $houseCategory;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function for deleting a record
    public function deleteHouseCategory(HouseCategory $houseCategory)
    {
        if (!$houseCategory) {
            session()->flash('error', 'House Category not found!');
            return;
        }

        // Delete the house category
        if ($this->confirmItemDelete) {
            $houseCategory->delete();
            $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'House Category successfully deleted!');

            // Redirect to the admin house categories page
            return redirect()->route('admin.house-categories');
        }
    }

    public function render()
    {
        return view('livewire.admin.house-categories.view-house-category');
    }
}
