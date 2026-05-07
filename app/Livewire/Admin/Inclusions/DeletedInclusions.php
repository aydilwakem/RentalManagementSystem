<?php

namespace App\Livewire\Admin\Inclusions;

use App\Models\PropertyFeature;
use Livewire\Component;

class DeletedInclusions extends Component
{
    //Public variable declaration to store soft deletes
    public $deletedInclusions;

    //Public declaration for confirmation modal
    public $confirmItemDelete = false;

    //Method to make modal true by fetching id
    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    //Mount all soft deleted features
    public function mount()
    {
        $this->fetchDeletedInclusions();
    }

    //Method to retrieve all soft deleted items and store them in the variable deletedfeatures
    public function fetchDeletedInclusions()
    {
        $this->deletedInclusions = PropertyFeature::onlyTrashed()
        ->where('property_type_id', 3) // Filter by property type 3 for event hall
        ->orderBy('created_at', 'ASC')
        ->get();
    }

    /**
     * Restores a deleted feature if it exists.
     * - Attempts to find and restore the feature with the given ID (including soft deleted records).
     * - If found, the feature is restored and a success message is flashed.
     * - Fetches the list of deleted features after restoration.
     */
    public function restoreInclusion($inclusionId)
    {
        $inclusion = PropertyFeature::withTrashed()->find($inclusionId);
        if ($inclusion) {
            $inclusion->restore();
            session()->flash('message', 'Feature restored successfully.');
            $this->fetchDeletedInclusions();
        }
    }

    /**
     * Permanently deletes a soft-deleted feature if confirmed.
     * - Finds the feature to be permanently deleted and deletes it.
     * - Displays a success message upon successful deletion.
     * - Fetches the list of deleted features after permanent deletion.
     * - Resets the confirmation flag after deletion.
     */
    public function deleteInclusionForever($inclusionId)
    {
        $inclusion = PropertyFeature::withTrashed()->find($this->confirmItemDelete);
        if ($inclusion) {
            $inclusion->forceDelete();
            session()->flash('message', 'Inclusion permanently deleted.');
            $this->fetchDeletedInclusions();
        }

        $this->confirmItemDelete = false;
    }

    /**
     * Renders the view with a list of deleted features.
     * - Retrieves fake IDs from the session and recalculates them if they are outdated or if the deleted features list has changed.
     * - Updates the session with the new fake IDs for use in the view.
     * - Returns the view with the list of deleted features and their corresponding fake IDs.
     */
    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_inclusion', []);

        $deletedIds = $this->deletedInclusions->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedInclusions as $index => $inclusion) {
                $fakeIDs[$inclusion->id] = 'INC-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_inclusion' => $fakeIDs]);
        }

        return view('livewire.admin.inclusions.deleted-inclusions', [
            'deletedInclusions' => $this->deletedInclusions,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
