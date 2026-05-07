<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class OldMaintenances extends Component
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
        ->whereNotNull('resolved_at')
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
        if (!session()->has('fake_ids_old_maintenances')) {
            session(['fake_ids_old_maintenances' => []]);
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
            session(['fake_ids_old_maintenances' => $fakeIDs]);

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
    $allOldMaintenances = Maintenance::all();

    $maintenances = $this->maintenances; 

    // Retrieve unique session for old maintenances
    $fakeIDs = session('fake_ids_old_maintenances', []);

    // Get only resolved maintenances
    $resolvedMaintenances = Maintenance::whereNotNull('resolved_at')->orderBy('created_at', 'ASC')->get();

    // Recalculate fake IDs if count mismatches
    if (count($fakeIDs) !== $resolvedMaintenances->count()) {
        $fakeIDs = [];
        foreach ($resolvedMaintenances as $index => $maintenance) {
            $fakeIDs[$maintenance->id] = 'MNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }
        session(['fake_ids_old_maintenances' => $fakeIDs]);
    }

    return view('livewire.admin.maintenance.old-maintenances', [
        'maintenances' => $maintenances,
        'fakeIDs' => $fakeIDs,
        'allOldMaintenances' => $allOldMaintenances,
    ]);
}

}
