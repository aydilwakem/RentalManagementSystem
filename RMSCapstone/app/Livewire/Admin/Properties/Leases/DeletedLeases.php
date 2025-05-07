<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Livewire\Component;

class DeletedLeases extends Component
{
    public $deletedLeases;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedLeases();
    }

    public function fetchDeletedLeases()
    {
    $this->deletedLeases = Transaction::onlyTrashed()
        ->where('reservation_type_id', 1) 
        ->with(['properties', 'transactionUser'])
        ->latest()
        ->get();
    }

    public function restoreLease($id)
    {  
        $lease = Transaction::onlyTrashed()->findOrFail($id);
        $lease->restore();

        session()->flash('message', 'Lease restored successfully.');
        //refresh
        $this->fetchDeletedLeases();
    }

    public function deleteLeaseForever($id)
    {
        $lease = Transaction::onlyTrashed()->findOrFail($id);
        $lease->forceDelete();

        session()->flash('message', 'Lease permanently deleted');
        $this->confirmItemDelete = false;
        // Optionally refresh the list
        $this->fetchDeletedLeases();
    }

    public function render()
    {
         // Generate fake IDs for deleted room rates
         $fakeIDs = session('fake_ids_leases', []);

         $deletedIds = $this->deletedLeases->pluck('id')->toArray();
 
         // Refresh fake IDs if mismatch or count changes
         if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
             $fakeIDs = [];
             foreach ($this->deletedLeases as $index => $lease) {
                 $fakeIDs[$lease->id] = 'LEASE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
             }
             session(['fake_ids_lease' => $fakeIDs]);
         }
        return view('livewire.admin.properties.leases.deleted-leases', [
            'deletedLeases' => $this->deletedLeases,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
