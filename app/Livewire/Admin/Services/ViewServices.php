<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewServices extends Component
{
    // ------------------------ Pagination and Sorting ---------------- //
    use WithPagination;
    #[Url(history: true)]
    public $search = '';
    #[Url()]
    public $perPage = 10;
    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';

    // --------------------- Modals ----------------------- //
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;
    public $selectedItemId = null;
    public $cannotDeleteItem = false;

    // --------------- Render Method ----------------------- //
    public function render()
    {
        $services = $this->services;
            // Retrieve unique session for services
        $fakeIDs = session('fake_ids_services', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== Service::count()) {
            $fakeIDs = [];
            foreach (Service::orderBy('created_at', 'ASC')->get() as $index => $service) {
                $fakeIDs[$service->id] = 'ACT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_services' => $fakeIDs]);
        }


        return view('livewire.admin.services.view-services', [
            'services' => $services,
            'fakeIDs' => $fakeIDs,
        ]);
    }

    // ---------------- Mount with Fake IDs ------------- //
    public function mount()
    {
        // Ensure services use a separate session key
        if (!session()->has('fake_ids_services')) {
            session(['fake_ids_services' => []]);
        }
    }

    // ---------------- Sort By Function ------------------------ //
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    //------------------------------------ Bulk Delete --------------------------------- //
    public $selectedRows = [];
    public $selectPageRows = false;


    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->services->pluck('id')->map(function ($id){
                return (string) $id;

            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getServicesProperty(){
        return Service::query()
        ->where('name', 'like', '%' . trim($this->search) . '%')
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
       $services = Service::whereIn('id', $this->selectedRows)->get();

        // Check if any selected service is active
        foreach ($services as $service) {
            if ($service->is_active) {
                $this->cannotDeleteItem = true;
                $this->confirmBulkDelete = false;
                return;
            }
        }

        try {
        //Check active event halls
        $usedInTransactions = Transaction::whereHas('services', function ($query) {
            $query->whereIn('service_id', $this->selectedRows);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Trigger modal
            $this->confirmBulkDelete = false;
            return;
        }

        // Bulk Delete
        Service::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected services got deleted!');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true; // FK error
        } else {
            throw $e;
        }
    }
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true;
    }

    // -------------------- Individual Delete ----------- // 
    public function confirmDelete($id)
    {
        $this->selectedItemId = $id;
        $this->confirmItemDelete = true;
    }

    public function deleteService(){
        $service = Service::find($this->selectedItemId);

    if (!$service) {
        session()->flash('error', 'Service not found.');
        return;
    }

    // Check if service is still active
    if ($service->is_active) {
            $this->cannotDeleteItem = true;
            $this->confirmItemDelete = null;
            return;
        }

    // Check if the service is active in Transactions
    $usedInTransactions = Transaction::whereHas('services', function ($query) use ($service) {
        $query->where('service_id', $service->id);
    })->exists();

    if ($usedInTransactions) {
        $this->cannotDeleteItem = true; // Show "Cannot delete" modal
        $this->confirmItemDelete = null; // Reset delete ID
        return;
    }

    try {

        // Delete the event hall
        $service->delete();

        // Reset confirmation modal
        $this->confirmItemDelete = false;
        $this->selectedItemId = null;

        // Refresh the list of event halls and regenerate fake ids
        $services = Service::orderBy('created_at', 'ASC')->get();
        $fakeIDs = [];
        foreach ($services as $index => $service) {
            $fakeIDs[$service->id] = 'SRV-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_services' => $fakeIDs]);

        // Flash success message
        session()->flash('message', 'Service successfully deleted!');
    }catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true;
        } else {
            throw $e;
        }
    }
    }


    // ------------------- Lazy Loading ------------------- //
    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }
}
