<?php

namespace App\Livewire\Admin\HouseCategories;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HouseCategory;
use App\Models\Property;
use Illuminate\Database\QueryException;

class ViewHouseCategories extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        // Ensure use a separate session key
        if (!session()->has('fake_ids_houseCategories')) {
            session(['fake_ids_houseCategories' => []]);
        }
    }

    public function deleteHouseCategory()
    {
        // Find the category by ID
        $houseCategory = HouseCategory::find($this->confirmItemDelete);

            // Check if the category is referenced in another table
        if (Property::where('house_category_id', $houseCategory->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $houseCategory->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Fetch remaining - sorted by creation date
            $houseCategory = HouseCategory::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($houseCategory as $index => $category) {
                $fakeIDs[$category->id] = 'HCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
           session(['fake_ids_houseCategory' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'House Category successfully deleted!');
    }catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) { 
                $this->cannotDeleteItem = true; // Show the cannot delete modal
            } else {
                throw $e; // Re-throw other exceptions
            }
        }
    }


    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $houseCategories = HouseCategory::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // Retrieve unique session 
        $fakeIDs = session('fake_ids_houseCategories', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== HouseCategory::count()) {
            $fakeIDs = [];
            foreach (HouseCategory::orderBy('created_at', 'ASC')->get() as $index => $houseCategoryItem) {
                $fakeIDs[$houseCategoryItem->id] = 'HCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_houseCategories' => $fakeIDs]);
        }

        return view('livewire.admin.house-categories.view-house-categories', [
            'houseCategories' => $houseCategories,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
