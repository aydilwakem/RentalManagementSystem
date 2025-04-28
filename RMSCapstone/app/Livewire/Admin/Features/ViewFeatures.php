<?php

namespace App\Livewire\Admin\Features;

use App\Models\PropertyFeature;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewFeatures extends Component
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
        if (!session()->has('fake_ids_features')) {
            session(['fake_ids_features' => []]);
        }
    }

    /**
     * Deletes an feature and updates the list of remaining features.
     * - Finds the feature by ID.
     * - If the feature exists and deletion is confirmed, it is deleted.
     * - The list of remaining features is fetched and sorted by creation date.
     * - Fake IDs for the features are recalculated and stored in the session.
     * - Displays a success message after the feature is successfully deleted.
     */
    public function deleteFeature($id)
    {
        $feature = PropertyFeature::find($id);

        if ($feature) {
            if ($this->confirmItemDelete) {
                PropertyFeature::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

                // Fetch remaining features - sorted by creation date, only type 2
                $features = PropertyFeature::where('property_type_id', 2)
                ->orderBy('created_at', 'ASC')
                ->get();


                // Reset fake IDs
                $fakeIDs = [];
                foreach ($features as $index => $featureItem) {
                    $fakeIDs[$featureItem->id] = 'FTR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                // Store updated fake IDs in a unique session key
                session(['fake_ids_features' => $fakeIDs]);

                session()->flash('message', 'Feature successfully deleted!');
            }
        }
    }

    /**
     * Sets the sorting criteria for displaying features.
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
    
    public function render()
    {
       // Retrieve features where property_type_id = 2, 
        $features = PropertyFeature::query()
        ->where('property_type_id', 2) // Only features with property_type_id = 2
        ->where('name', 'like', "%{$this->search}%")
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);

        // Retrieve fake IDs from session
        $fakeIDs = session('fake_ids_features', []);

        // Recalculate fake IDs only if the count mismatches for features with property_type_id = 2
        if (count($fakeIDs) !== $features->total()) {
            // Reset the fake IDs array
            $fakeIDs = [];

        // Fetch only features with property_type_id = 2
        $featuresWithPropertyType2 = PropertyFeature::where('property_type_id', 2)
            ->orderBy('created_at', 'ASC')
            ->get();

        // Recalculate fake IDs for features with property_type_id = 2
        foreach ($featuresWithPropertyType2 as $index => $featureItem) {
            $fakeIDs[$featureItem->id] = 'FTR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_features' => $fakeIDs]);
        }

        // Return the view with features and their respective fake IDs
        return view('livewire.admin.features.view-features', [
            'features' => $features,
            'fakeIDs' => $fakeIDs,
        ]);
    }

}
