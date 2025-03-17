<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\Amenity;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewAmenities extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public function mount()
    {
        // Ensure use a separate session key
        if (!session()->has('fake_ids_amenities')) {
            session(['fake_ids_amenities' => []]);
        }
    }

    public function deleteAmenity($id)
    {
        $amenity = Amenity::find($id);

        if ($amenity) {
            $amenity->delete();

             // Fetch remaining - sorted by creation date
             $amenity = Amenity::orderBy('created_at', 'ASC')->get();

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
        $amenities = Amenity::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

            // Retrieve unique session 
        $fakeIDs = session('fake_ids_amenities', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Amenity::count()) {
            $fakeIDs = [];
            foreach (Amenity::orderBy('created_at', 'ASC')->get() as $index => $amenityItem) {
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
