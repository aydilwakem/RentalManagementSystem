<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Component;

class DeletedMaintenances extends Component
{
    public $deletedMaintenances;
    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedMaintenances();
    }

    public function fetchDeletedMaintenances()
    {
        $this->deletedMaintenances = Maintenance::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreMaintenance($maintenanceId)
    {
        $maintenance = Maintenance::withTrashed()->find($maintenanceId);
        if ($maintenance) {
            $maintenance->restore();
            session()->flash('message', 'Maintenance restored successfully.');
            $this->fetchDeletedMaintenances();
        }
    }

    public function deleteMaintenanceForever($maintenanceId)
    {
        $maintenance = Maintenance::withTrashed()->find($this->confirmItemDelete);
        if ($maintenance) {
            $maintenance->forceDelete();
            session()->flash('message', 'Maintenance permanently deleted.');
            $this->fetchDeletedMaintenances();
        }
        $this->confirmItemDelete = false;
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_maintenance', []);

        $deletedIds = $this->deletedMaintenances->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedMaintenances as $index => $maintenance) {
                $fakeIDs[$maintenance->id] = 'MNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_event' => $fakeIDs]);
        }

        return view('livewire.admin.maintenance.deleted-maintenances', [
            'deletedMaintenances' => $this->deletedMaintenances,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
