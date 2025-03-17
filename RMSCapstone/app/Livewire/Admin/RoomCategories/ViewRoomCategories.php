<?php

namespace App\Livewire\Admin\RoomCategories;

use Livewire\Component;
use App\Models\RoomCategory;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ViewRoomCategories extends Component
{

    use WithPagination;

    #[Url(history:true)]
    public $sortBy = 'created_at';

    #[Url(history:true)]
    public $sortDir = 'DESC';

    #[Url(history:true)]
    public $search = '';
    public $perPage = 5;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }


    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_roomCategory')) {
            session(['fake_ids_roomCategory' => []]);
        }
    }

    public function deleteCategory($id)
    {
        // Find the room category by ID
        $roomCategory = RoomCategory::find($id);

        if ($roomCategory) {
            // Detach related amenities before deleting
            $roomCategory->amenities()->detach();

            // Delete the room category
            if ($this->confirmItemDelete) {
                RoomCategory::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

            // Fetch remaining - sorted by creation date
            $roomCategory = RoomCategory::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($roomCategory as $index => $category) {
                $fakeIDs[$category->id] = 'RCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
           session(['fake_ids_roomCategory' => $fakeIDs]);


            // Flash success message
            session()->flash('message', 'Room Category successfully deleted!');
        }
    }
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
        $roomCategories = RoomCategory::query()
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

             // Retrieve unique session 
        $fakeIDs = session('fake_ids_roomCategory', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== RoomCategory::count()) {
            $fakeIDs = [];
            foreach (RoomCategory::orderBy('created_at', 'ASC')->get() as $index => $category) {
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
