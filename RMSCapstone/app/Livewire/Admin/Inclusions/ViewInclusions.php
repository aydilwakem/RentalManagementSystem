<?php

namespace App\Livewire\Admin\Inclusions;

use App\Models\PropertyFeature;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewInclusions extends Component
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
    public $selectedInclusionId = null;


    //public declaration for bulk actions
    public $selectedRows = [];
    public $selectPageRows = false;

    //lazy loading
    public function placeholder()
    {
        return view('livewire.admin.placeholder-sm');
    }

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->inclusions->pluck('id')->map(function ($id) {
                return (string) $id;
            })->toArray();;
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getInclusionsProperty()
    {
        return PropertyFeature::query()
            ->where('property_type_id', 3) // Only features with property_type_id = 3
            ->where('name', 'like', '%' . trim($this->search) . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    public function deleteSelectedRows()
    {
        PropertyFeature::whereIn('id', $this->selectedRows)->delete();
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected inclusions got deleted!');
    }

    public function confirmDeleteInBulk()
    {
        $this->confirmBulkDelete = true;
    }

    public function confirmDelete($id)
    {
        $this->selectedInclusionId = $id;
        $this->confirmItemDelete = true;
    }

    //Method for session of fake ids
    public function mount()
    {
        // Ensure use a separate session key
        if (!session()->has('fake_ids_inclusions')) {
            session(['fake_ids_inclusions' => []]);
        }
    }

    /**
     * Deletes an inclusion and updates the list of remaining inclusion.
     * - Finds the inclusion by ID.
     * - If the inclusion exists and deletion is confirmed, it is deleted.
     * - The list of remaining inclusion is fetched and sorted by creation date.
     * - Fake IDs for the inclusion are recalculated and stored in the session.
     * - Displays a success message after the inclusion is successfully deleted.
     */
    public function deleteInclusion()
    {
        $inclusion = PropertyFeature::find($this->selectedInclusionId);

        if ($inclusion && $this->confirmItemDelete) {
            $inclusion->delete();

            $this->confirmItemDelete = false;
            $this->selectedInclusionId = null;

            // Refresh and rebuild fake IDs
            $inclusions = PropertyFeature::where('property_type_id', 3)
                ->orderBy('created_at', 'ASC')
                ->get();

            $fakeIDs = [];
            foreach ($inclusions as $index => $item) {
                $fakeIDs[$item->id] = 'INC-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            session(['fake_ids_inclusions' => $fakeIDs]);

            session()->flash('message', 'Inclusion successfully deleted!');
        }
    }


    /**
     * Sets the sorting criteria for displaying inclusions.
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
        // Retrieve inclusions where property_type_id = 3,
        $inclusions = $this->inclusions;

        // Retrieve fake IDs from session
        $fakeIDs = session('fake_ids_inclusions', []);

        // Recalculate fake IDs only if the count mismatches for features with property_type_id = 3
        if (count($fakeIDs) !== $inclusions->total()) {
            // Reset the fake IDs array
            $fakeIDs = [];

            // Fetch only features with property_type_id = 3
            $inclusionsWithPropertyType3 = PropertyFeature::where('property_type_id', 3)
                ->orderBy('created_at', 'ASC')
                ->get();

            // Recalculate fake IDs for inclusions with property_type_id = 3
            foreach ($inclusionsWithPropertyType3 as $index => $inclusionItem) {
                $fakeIDs[$inclusionItem->id] = 'INC-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in session
            session(['fake_ids_inclusions' => $fakeIDs]);
        }

        // Return the view with inclusions and their respective fake IDs
        return view('livewire.admin.inclusions.view-inclusions', [
            'inclusions' => $inclusions,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
