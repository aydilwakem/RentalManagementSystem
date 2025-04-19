<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewAmenities extends Component
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

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        // Ensure use a separate session key
        if (!session()->has('fake_ids_amenities')) {
            session(['fake_ids_amenities' => []]);
        }
    }

    public function deleteAmenity($id)
    {
        $amenity = PropertyFeature::find($id);

        if ($amenity) {
            if ($this->confirmItemDelete) {
                PropertyFeature::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

                // Fetch remaining - sorted by creation date
                $amenity = PropertyFeature::orderBy('created_at', 'ASC')->get();

                // Reset fake IDs
                $fakeIDs = [];
                foreach ($amenity as $index => $amenityItem) {
                    $fakeIDs[$amenityItem->id] = 'AMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                // Store updated fake IDs in a unique session key
                session(['fake_ids_amenities' => $fakeIDs]);

                session()->flash('message', 'Amenity successfully deleted!');
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
        $amenities = PropertyFeature::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // Retrieve unique session 
        $fakeIDs = session('fake_ids_amenities', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== PropertyFeature::count()) {
            $fakeIDs = [];
            foreach (PropertyFeature::orderBy('created_at', 'ASC')->get() as $index => $amenityItem) {
                $fakeIDs[$amenityItem->id] = 'AMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_amenities' => $fakeIDs]);
        }

        return view('livewire.admin.amenities.view-amenities', [
            'amenities' => $amenities,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
