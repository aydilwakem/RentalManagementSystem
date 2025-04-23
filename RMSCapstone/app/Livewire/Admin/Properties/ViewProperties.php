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
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $perPage = 10;
    public $statusFilter = '';

    public $confirmItemDelete = false;

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
        if ($this->confirmItemDelete) {
            $house = Property::find($this->confirmItemDelete);

            if ($house) {
                $house->features()->detach();

                $house->delete();

                $this->confirmItemDelete = false;

                $houses = Property::ofType('House')->orderBy('created_at', 'ASC')->get();

                $fakeIDs = [];
                foreach ($houses as $index => $houseItem) {
                    $fakeIDs[$houseItem->id] = 'HS-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                session(['fake_ids_houses' => $fakeIDs]);

                session()->flash('message', 'House successfully deleted!');
            } else {
                session()->flash('error', 'House not found!');
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

        $houses = Property::query()
            ->ofType('House')
            ->when($this->statusFilter, function ($query) {
                $query->where('property_status', $this->statusFilter);
            })
            ->where('name_number', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

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
