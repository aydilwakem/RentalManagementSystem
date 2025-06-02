<?php

namespace App\Livewire\Admin\RoomCategories;

use App\Models\Property;
use App\Models\Room;
use Livewire\Component;
use App\Models\PropertyCategory;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ViewRoomCategories extends Component
{

    use WithPagination;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url(history: true)]
    public $search = '';
    public $perPage = 10;

    public $confirmItemDelete = false;
    public $selectedItemId = null;
    public $cannotDeleteItem = false; //Modal for cannot delete due to integrity constraint

    public $confirmBulkDelete = false;

    //public declaration for bulk actions
    public $selectedRows = [];
    public $selectPageRows = false;

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->roomCategories->pluck('id')->map(function ($id){
                return (string) $id;

            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getRoomCategoriesProperty(){
        return PropertyCategory::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
         try {
        // Check if any of the selected Event Types are used in transactions
        $usedInRooms = Property::whereIn('property_category_id', $this->selectedRows)->exists();

        if ($usedInRooms) {
            $this->cannotDeleteItem = true; // Trigger "can't delete" modal
            $this->confirmBulkDelete = false;
            return;
        }

        // Proceed with bulk deletion
        PropertyCategory::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected halls got deleted!');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true; // Foreign key violation
        } else {
            throw $e; // Let other exceptions bubble up
        }
    }
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true;
    }

    public function confirmDelete($id)
    {
        $this->selectedItemId = $id;
        $this->confirmItemDelete = true;
    }


    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_roomCategory')) {
            session(['fake_ids_roomCategory' => []]);
        }
    }

    public function deleteRoomCategory()
    {
        $roomCategory = PropertyCategory::find($this->selectedItemId);

        if (!$roomCategory) {
            session()->flash('error', 'Room Category not found.');
            return;
        }

        // Check if the category is referenced in another table
        if (Property::where('property_category_id', $roomCategory->id)->exists()) {
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $roomCategory->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = false;
            $this->selectedItemId = null;

            // Refresh event categories
            $roomCategories = PropertyCategory::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($roomCategories as $index => $category) {
                $fakeIDs[$category->id] = 'RCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store session of the fake IDs
            session(['fake_ids_roomCategory' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');
        }catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) {
                $this->cannotDeleteItem = true; // Show the cannot delete modal
            } else {
                throw $e; // Re-throw other exceptions
            }
        }
    }

    public function placeholder(){
        return view('livewire.admin.placeholder');
    }


    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        $roomCategories = $this->roomCategories;
        // Retrieve unique session
        $fakeIDs = session('fake_ids_roomCategory', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== PropertyCategory::count()) {
            $fakeIDs = [];
            foreach (PropertyCategory::orderBy('created_at', 'ASC')->get() as $index => $category) {
                $fakeIDs[$category->id] = 'RCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_roomCategory' => $fakeIDs]);
        }

        return view('livewire.admin.room-categories.view-room-categories', [
            'roomCategories' => $roomCategories,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
