<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewMaintenances extends Component
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

    public $priorityStatus = '';

    public function mount()
    {
        // Ensure maintenance use a separate session key
        if (!session()->has('fake_ids_maintenances')) {
            session(['fake_ids_maintenances' => []]);
        }
    }


    public function deleteMaintenances($id)
    {
        // Find the maintenance by ID
        $maintenances = Maintenance::find($id);

            if ($maintenances) {
                // Delete the maintenance
                $maintenances->delete();

            // Fetch remaining maintenance - sorted by creation date
             $maintenances = Maintenance::orderBy('created_at', 'ASC')->get();

             // Reset fake IDs
             $fakeIDs = [];
             foreach ($maintenances as $index => $maintenance) {
                 $fakeIDs[$maintenance->id] = 'MNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
             }
 
             // Store updated fake IDs in a unique session key
            session(['fake_ids_maintenances' => $fakeIDs]);


            // Flash success message
            session()->flash('message', 'Maintenance successfully deleted!');
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
        $maintenances = Maintenance::query()
            ->search($this->search)
            ->when($this->priorityStatus !== '', function ($query) {
                $query->where('priority_status', $this->priorityStatus);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

            // Retrieve unique session for activities
        $fakeIDs = session('fake_ids_maintenances', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Maintenance::count()) {
            $fakeIDs = [];
            foreach (Maintenance::orderBy('created_at', 'ASC')->get() as $index => $maintenance) {
                $fakeIDs[$maintenance->id] = 'MNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_maintenances' => $fakeIDs]);
        }


        return view('livewire.admin.maintenance.view-maintenances', [
            'maintenances' => $maintenances,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
