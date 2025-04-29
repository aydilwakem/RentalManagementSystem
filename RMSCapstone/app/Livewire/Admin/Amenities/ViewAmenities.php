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
    public $confirmBulkDelete = false; 

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->amenities->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 
    }

    public function getAmenitiesProperty(){
        return PropertyFeature::query()
        ->where('property_type_id', 1) // Only amenities with property_type_id = 1
        ->where('name', 'like', "%{$this->search}%")
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
        PropertyFeature::whereIn('id', $this->selectedRows)->delete(); 
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected amenities got deleted!');
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true; 
    }

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

                // Fetch remaining amenities - sorted by creation date, only type 1
                $amenity = PropertyFeature::where('property_type_id', 1)
                ->orderBy('created_at', 'ASC')
                ->get();


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
       // Retrieve amenities where property_type_id = 1, 
        $amenities = $this->amenities; 

        // Retrieve fake IDs from session
        $fakeIDs = session('fake_ids_amenities', []);

        // Recalculate fake IDs only if the count mismatches for amenities with property_type_id = 1
        if (count($fakeIDs) !== $amenities->total()) {
            $fakeIDs = [];

        // Fetch only amenities with property_type_id = 1
        $amenitiesWithPropertyType1 = PropertyFeature::where('property_type_id', 1)
            ->orderBy('created_at', 'ASC')
            ->get();

        // Recalculate fake IDs for amenities with property_type_id = 1
        foreach ($amenitiesWithPropertyType1 as $index => $amenityItem) {
            $fakeIDs[$amenityItem->id] = 'AMY-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_amenities' => $fakeIDs]);
        }

        // Return the view with amenities and their respective fake IDs
        return view('livewire.admin.amenities.view-amenities', [
            'amenities' => $amenities,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
