<?php

namespace App\Livewire\Admin\HouseCategories;

use App\Models\HouseCategory;
use Livewire\Component;

class DeletedHouseCategories extends Component
{
    public $deletedHouseCategories;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedHouseCategories();
    }

    public function fetchDeletedHouseCategories()
    {
        $this->deletedHouseCategories = HouseCategory::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreHouseCategory($houseCategoryId)
    {
        $houseCategory = HouseCategory::withTrashed()->find($houseCategoryId);
        if ($houseCategory) {
            $houseCategory->restore();
            session()->flash('message', 'Room category restored successfully.');
            $this->fetchDeletedHouseCategories();
        }
    }

    public function deleteHouseCategoryForever($houseCategoryId)
    {
        $houseCategory = HouseCategory::withTrashed()->find($this->confirmItemDelete);
        if ($houseCategory) {
            $houseCategory->forceDelete();
            session()->flash('message', 'House category permanently deleted.');
            $this->fetchDeletedHouseCategories();
        }
        $this->confirmItemDelete = false;
    }


    public function render()
    {
         // Create or reuse fake IDs for ONLY deleted records
         $fakeIDs = session('fake_ids_houseCategory', []);

         $deletedIds = $this->deletedHouseCategories->pluck('id')->toArray();
 
         // Recalculate fake IDs if mismatch or deleted list has changed
         if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
             $fakeIDs = [];
             foreach ($this->deletedHouseCategories as $index => $category) {
                 $fakeIDs[$category->id] = 'HCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
             }
             session(['fake_ids_houseCategory' => $fakeIDs]);
         }
        return view('livewire.admin.house-categories.deleted-house-categories', [
            'deletedHouseCategories' => $this->deletedHouseCategories,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
