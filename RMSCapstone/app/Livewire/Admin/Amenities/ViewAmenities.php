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
    public $sortDir = 'ASC';

    public function deleteAmenity($id)
    {
        $amenity = Amenity::find($id);

        if ($amenity) {
            $amenity->delete();
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

        return view('livewire.admin.amenities.view-amenities', compact('amenities'));
    }
}
