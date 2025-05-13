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

    #[Url]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $priorityStatus = '';

    public $confirmItemDelete = false;
    public $confirmBulkDelete = false; 

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->maintenances->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 

    }

    public function getMaintenancesProperty(){
        return Maintenance::query()
        ->whereNull('resolved_at')
        ->search($this->search)
        ->when($this->priorityStatus !== '', function ($query) {
            $query->where('priority_status', $this->priorityStatus);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
        Maintenance::whereIn('id', $this->selectedRows)->delete(); 
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected maintenances got deleted!');
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true; 
    }

    public function mount()
    {
        // Ensure maintenance use a separate session key
        if (!session()->has('fake_ids_pending_maintenances')) {
            session(['fake_ids_pending_maintenances' => []]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteMaintenances()
    {
        if ($this->confirmItemDelete) {
            Maintenance::find($this->confirmItemDelete)?->delete();
            $this->confirmItemDelete = false;

            // Fetch remaining maintenance - sorted by creation date
            $maintenances = Maintenance::orderBy('created_at', 'ASC')->get();

            // Reset fake IDs
            $fakeIDs = [];
            foreach ($maintenances as $index => $maintenance) {
                $fakeIDs[$maintenance->id] = 'MNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in a unique session key
            session(['fake_ids_pending_maintenances' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'Maintenance successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    public function render()
{
    $allMaintenances = Maintenance::all();

    //checks if the resolved at field is null
    $maintenances = $this->maintenances; 

    // Retrieve unique session for pending maintenances
    $fakeIDs = session('fake_ids_pending_maintenances', []);

    // Get only pending maintenances where field is null
    $pendingMaintenances = Maintenance::whereNull('resolved_at')->orderBy('created_at', 'ASC')->get();

    // recalculation
    if (count($fakeIDs) !== $pendingMaintenances->count()) {
        $fakeIDs = [];
        foreach ($pendingMaintenances as $index => $maintenance) {
            $fakeIDs[$maintenance->id] = 'MNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }
        session(['fake_ids_pending_maintenances' => $fakeIDs]);
    }

    return view('livewire.admin.maintenance.view-maintenances', [
        'maintenances' => $maintenances,
        'fakeIDs' => $fakeIDs,
        'allMaintenances' => $allMaintenances,
    ]);
}


}
