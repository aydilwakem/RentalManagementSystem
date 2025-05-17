<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewProperties extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $perPage = 10;
    public $statusFilter = '';

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;
    public $confirmBulkDelete = false; 

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->houses->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 
    }

    public function getHousesProperty(){
        return Property::query()
        ->ofType('House')
        ->when($this->statusFilter, function ($query) {
            $query->where('property_status', $this->statusFilter);
        })
        ->where('name_number', 'like', '%' . trim($this->search) . '%')
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
       try {
        //Check active event halls
        $usedInTransactions = Transaction::whereHas('properties', function ($query) {
            $query->whereIn('property_id', $this->selectedRows);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Trigger modal
            $this->confirmBulkDelete = false;
            return;
        }

        // Detach features before deleting
        $properties = Property::whereIn('id', $this->selectedRows)->get();
        foreach ($properties as $property) {
            $property->features()->detach();
        }

        // Bulk Delete
        Property::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected halls got deleted!');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true; // FK error
        } else {
            throw $e; 
        }
    }
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
        if (!session()->has('fake_ids_houses')) {
            session(['fake_ids_houses' => []]);
        }
    }

    public function deleteHouse()
    {
        $house = Property::find($this->confirmItemDelete);

    if (!$house) {
        session()->flash('error', 'House not found.');
        return;
    }

    // Check if the house is active in Leases
    $usedInTransactions = Transaction::whereHas('properties', function ($query) use ($house) {
        $query->where('property_id', $house->id);
    })->exists();

    if ($usedInTransactions) {
        $this->cannotDeleteItem = true; // Show "Cannot delete" modal
        $this->confirmItemDelete = null; // Reset delete ID
        return;
    }

    try {
        $house->features()->detach();

        // Delete the event hall
        $house->delete();

        // Reset confirmation modal
        $this->confirmItemDelete = null;

        // Refresh the list of event halls and regenerate fake ids
        $houses = Property::orderBy('created_at', 'ASC')->get();
        $fakeIDs = [];
        foreach ($houses as $index => $house) {
            $fakeIDs[$house->id] = 'HS-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_houses' => $fakeIDs]);

        // Flash success message
        session()->flash('message', 'House successfully deleted!');
    }catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true;
        } else {
            throw $e;
        }
    }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    public function render()
    {
        $allHouses = Property::ofType('House')->get();

        $houses = $this->houses; 

        $fakeIDs = session('fake_ids_houses', []);

        if (count($fakeIDs) !== Property::ofType('House')->count()) {
            $fakeIDs = [];
            foreach (Property::ofType('House')->orderBy('created_at', 'ASC')->get() as $index => $houseItem) {
                $fakeIDs[$houseItem->id] = 'HS-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_houses' => $fakeIDs]);
        }

        return view('livewire.admin.properties.view-properties', [
            'allHouses' => $allHouses,
            'houses' => $houses,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
