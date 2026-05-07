<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Component;

class DeletedServices extends Component
{
    // ------------------ Varable Declaration ------------------ //
    public $deletedServices;

    // ------------------- Modal Declaration ------------------- //
    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    // ------------------------ Mount ------------------------- //
    public function mount(){
        $this->fetchDeletedServices(); 
    }

    // -------------------- Fetch Method -------------------- //
    public function fetchDeletedServices(){
        $this->deletedServices = Service::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    // ---------------- Restore Method ---------------- //
    public function restoreService($serviceId)
    {
        $service = Service::withTrashed()->find($serviceId);
        if ($service) {
            $service->restore();
            session()->flash('message', 'Service restored successfully.');
            $this->fetchDeletedServices();
        }
    }

    // --------------------- Delete Forever Method ---------------------//
    public function deleteServiceForever($serviceId){
        $service = Service::withTrashed()->find($this->confirmItemDelete);
        if ($service) {
            $service->forceDelete();
            session()->flash('message', 'Service permanently deleted.');
            $this->fetchDeletedServices();
        }
        //Closes the modal
        $this->confirmItemDelete = false;
    }


    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_services', []);

        //Fetches all ids into array
        $deletedIds = $this->deletedServices->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedServices as $index => $service) {
                $fakeIDs[$service->id] = 'SRV-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_services' => $fakeIDs]);
        }

        return view('livewire.admin.services.deleted-services', [
            'deletedServices' => $this->deletedServices,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
