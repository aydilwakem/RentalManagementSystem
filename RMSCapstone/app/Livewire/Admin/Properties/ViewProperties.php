<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewProperties extends Component
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

    public $availability = '';

    public $confirmItemDelete = false;

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

    public function deleteProperty($id)
    {
        $property = Property::find($id);
        if ($property && $this->confirmItemDelete) {
            $property->delete();
            $this->confirmItemDelete = false;

            // Refresh fake IDs
            $properties = Property::orderBy('created_at', 'ASC')->get();
            $fakeIDs = [];
            foreach ($properties as $index => $property) {
                $fakeIDs[$property->id] = 'PRP-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_properties' => $fakeIDs]);

            session()->flash('message', 'Property successfully deleted!');
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
