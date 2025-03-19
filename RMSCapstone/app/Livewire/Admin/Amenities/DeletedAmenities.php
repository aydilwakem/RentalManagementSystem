<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\Amenity;
use Livewire\Component;

class DeletedAmenities extends Component
{
    public $deletedAmenities;

    public function mount()
    {
        $this->fetchDeletedAmenities();
    }

    public function fetchDeletedAmenities()
    {
        $this->deletedAmenities = Amenity::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreAmenity($amenityId)
    {
        $amenity = Amenity::withTrashed()->find($amenityId);
        if ($amenity) {
            $amenity->restore();
            session()->flash('message', 'Room restored successfully.');
            $this->fetchDeletedAmenities();
        }
    }

    public function deleteAmenityForever($amenityId)
    {
        $amenity = Amenity::withTrashed()->find($amenityId);
        if ($amenity) {
            $amenity->forceDelete();
            session()->flash('message', 'Room permanently deleted.');
            $this->fetchDeletedAmenities();
        }
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_amenity', []);

        $deletedIds = $this->deletedAmenities->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedAmenities as $index => $category) {
                $fakeIDs[$category->id] = 'AMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_roomCategory' => $fakeIDs]);
        }

        return view('livewire.admin.amenities.deleted-amenities', [
            'deletedAmenities' => $this->deletedAmenities,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
