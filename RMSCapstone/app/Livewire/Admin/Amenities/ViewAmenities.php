<?php

namespace App\Livewire\Admin\Amenities;

use App\Models\PropertyFeature;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewAmenities extends Component
{
    //Declarations for pagination and sorting
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    //Public declaration for confirmation modal
    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    //Method for session of fake ids
    public function mount()
    {
        // Ensure use a separate session key
        if (!session()->has('fake_ids_amenities')) {
            session(['fake_ids_amenities' => []]);
        }
    }

    /**
     * Deletes an amenity and updates the list of remaining amenities.
     * - Finds the amenity by ID.
     * - If the amenity exists and deletion is confirmed, it is deleted.
     * - The list of remaining amenities is fetched and sorted by creation date.
     * - Fake IDs for the amenities are recalculated and stored in the session.
     * - Displays a success message after the amenity is successfully deleted.
     */
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

    /**
     * Sets the sorting criteria for displaying amenities.
     * - If the current sorting field matches the selected one, toggle the sort direction between "ASC" and "DESC".
     * - If it's a new field, set the sorting direction to "ASC" by default.
     */
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    /**
     * Renders the view with a paginated list of amenities, applying search and sorting criteria.
     * - Filters amenities by name using a search term.
     * - Applies sorting based on the selected field and direction.
     * - Recalculates fake IDs for the amenities if the count of fake IDs doesn't match the actual count of amenities.
     * - Updates the session with the recalculated fake IDs and passes the data to the view.
     */
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
