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
        PropertyCategory::whereIn('id', $this->selectedRows)->delete(); 
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected room categories got deleted!');
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true; 
    }

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
        $roomCategory = PropertyCategory::find($id);

        if ($roomCategory) {
            if ($this->confirmItemDelete) {
                PropertyCategory::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

                $roomCategory = PropertyCategory::orderBy('created_at', 'ASC')->get();

                $fakeIDs = [];
                foreach ($roomCategory as $index => $category) {
                    $fakeIDs[$category->id] = 'RCT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                session(['fake_ids_roomCategory' => $fakeIDs]);

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
