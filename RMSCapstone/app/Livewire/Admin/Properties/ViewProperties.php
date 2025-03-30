<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewProperties extends Component
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

    public $availability = '';

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Modal will appear if house is being used by a Tenant

    public function mount()
    {
        if (!session()->has('fake_ids_properties')) {
            session(['fake_ids_properties' => []]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteProperty()
    {
        // Find the room category by ID
        $property = Property::find($this->confirmItemDelete);

            // Check if the category is referenced in another table
        if (Tenant::where('house_id', $property->id)->exists()) { 
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $property->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Fetch remaining - sorted by creation date
            $property = Property::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($property as $index => $propertyItem) {
                $fakeIDs[$propertyItem->id] = 'PRT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
           session(['fake_ids_properties' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'House successfully deleted!');
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
        $properties = Property::query()
            ->where('name', 'like', "%{$this->search}%")
            ->when($this->availability !== '', function ($query) {
                $query->where('availability', $this->availability);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $fakeIDs = session('fake_ids_properties', []);
        if (count($fakeIDs) !== Property::count()) {
            $fakeIDs = [];
            foreach (Property::orderBy('created_at', 'ASC')->get() as $index => $property) {
                $fakeIDs[$property->id] = 'PRT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_properties' => $fakeIDs]);
        }

        return view('livewire.admin.properties.view-properties', [
            'properties' => $properties,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
