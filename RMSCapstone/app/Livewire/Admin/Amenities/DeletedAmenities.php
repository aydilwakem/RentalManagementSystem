<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Component;

class DeletedAmenities extends Component
{
    //Public variable declaration to store soft deletes
    public $deletedAmenities;

    //Public declaration for confirmation modal
    public $confirmItemDelete = false;

    //Method to make modal true by fetching id
    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    //Mount all soft deleted amenities
    public function mount()
    {
        $this->fetchDeletedAmenities();
    }

     //Method to retrieve all soft deleted items and store them in the variable deletedAmenities
    public function fetchDeletedAmenities()
    {
        $this->deletedAmenities = PropertyFeature::onlyTrashed()
        ->where('property_type_id', 1) // Filter by property type 1
        ->orderBy('created_at', 'ASC')
        ->get();
    }

    /**
     * Restores a deleted amenity if it exists.
     * - Attempts to find and restore the amenity with the given ID (including soft deleted records).
     * - If found, the amenity is restored and a success message is flashed.
     * - Fetches the list of deleted amenities after restoration.
     */
    public function restoreAmenity($amenityId)
    {
        $amenity = PropertyFeature::withTrashed()->find($amenityId);
        if ($amenity) {
            $amenity->restore();
            session()->flash('message', 'Amenity restored successfully.');
            $this->fetchDeletedAmenities();
        }
    }

    /**
     * Permanently deletes a soft-deleted amenity if confirmed.
     * - Finds the amenity to be permanently deleted and deletes it.
     * - Displays a success message upon successful deletion.
     * - Fetches the list of deleted amenities after permanent deletion.
     * - Resets the confirmation flag after deletion.
     */
    public function deleteAmenityForever($amenityId)
    {
        $amenity = PropertyFeature::withTrashed()->find($this->confirmItemDelete);
        if ($amenity) {
            $amenity->forceDelete();
            session()->flash('message', 'Amenity permanently deleted.');
            $this->fetchDeletedAmenities();
        }

        $this->confirmItemDelete = false;
    }

    /**
     * Renders the view with a list of deleted amenities.
     * - Retrieves fake IDs from the session and recalculates them if they are outdated or if the deleted amenities list has changed.
     * - Updates the session with the new fake IDs for use in the view.
     * - Returns the view with the list of deleted amenities and their corresponding fake IDs.
     */
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
            session(['fake_ids_amenity' => $fakeIDs]);
        }

        return view('livewire.admin.amenities.deleted-amenities', [
            'deletedAmenities' => $this->deletedAmenities,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
