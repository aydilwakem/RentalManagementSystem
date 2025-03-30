<?php

namespace App\Livewire\Admin\HouseCategories;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HouseCategory;

class ViewHouseCategories extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'ASC';

    public $confirmItemDelete = false;

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

    public function deleteHouseCategory($id)
    {
        $houseCategory = HouseCategory::find($id);

        if ($houseCategory) {
            if ($this->confirmItemDelete) {
                HouseCategory::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

                // Fetch remaining - sorted by creation date
                $houseCategory = HouseCategory::orderBy('created_at', 'ASC')->get();

                // Reset fake IDs
                $fakeIDs = [];
                foreach ($houseCategory as $index => $houseCategoryItem) {
                    $fakeIDs[$houseCategoryItem->id] = 'HCY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                // Store updated fake IDs in a unique session key
                session(['fake_ids_houseCategories' => $fakeIDs]);

                session()->flash('message', 'House Category successfully deleted!');
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
                $fakeIDs[$houseCategoryItem->id] = 'AMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_houseCategories' => $fakeIDs]);
        }

        return view('livewire.admin.house-categories.view-house-categories', [
            'houseCategories' => $houseCategories,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
