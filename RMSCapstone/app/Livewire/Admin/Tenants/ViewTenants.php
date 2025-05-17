<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Transaction;
use App\Models\TransactionUser;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewTenants extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $confirmItemDelete = false;
    public $confirmBulkDelete = false; 
    public $cannotDeleteItem = false; //Modal for cannot delete for tenants with active lease

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->tenants->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 
    }

    public function getTenantsProperty(){
        return TransactionUser::query()
        ->where(function ($query) {
            $query->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$this->search}%"])
                ->orWhere('email', 'like', "%{$this->search}%");
        })
        ->where('trn_user_type', 'tenant')
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
       try {
        //Check active event halls
        $usedInTransactions = Transaction::whereHas('transactionUser', function ($query) {
            $query->whereIn('created_by', $this->selectedRows);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Trigger modal
            $this->confirmBulkDelete = false;
            return;
        }

        // Bulk Delete
        TransactionUser::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected tenants got deleted!');
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

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        if (!session()->has('fake_ids_tenants')) {
            session(['fake_ids_tenants' => []]);
        }
    }

    public function deleteTenant($id)
    {
        $tenant = TransactionUser::find($this->confirmItemDelete);

        if (!$tenant) {
            session()->flash('error', 'Tenant not found.');
            return;
        }

        // Check if the event hall is active in Events
        $usedInTransactions = Transaction::whereHas('transactionUser', function ($query) use ($tenant) {
            $query->where('created_by', $tenant->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; // Show "Cannot delete" modal
            $this->confirmItemDelete = null; // Reset delete ID
            return;
        }

        try {

            // Delete the event hall
            $tenant->delete();

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            // Refresh the list of event halls and regenerate fake ids
            $tenants = TransactionUser::orderBy('created_at', 'ASC')->get();
            $fakeIDs = [];
            foreach ($tenants as $index => $tenant) {
                $fakeIDs[$tenant->id] = 'TNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            // Store updated fake IDs in session
            session(['fake_ids_tenants' => $fakeIDs]);

            // Flash success message
            session()->flash('message', 'Tenant successfully deleted!');
        }catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->cannotDeleteItem = true;
            } else {
                throw $e;
            }
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
        $tenants = $this->tenants;

        $fakeIDs = session('fake_ids_tenants', []);

        if (count($fakeIDs) !== TransactionUser::count()) {
            $fakeIDs = [];
            foreach (TransactionUser::orderBy('created_at', 'ASC')->get() as $index => $tenantItem) {
                $fakeIDs[$tenantItem->id] = 'TNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_tenants' => $fakeIDs]);
        }

        return view('livewire.admin.tenants.view-tenants', [
            'tenants' => $tenants,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
