<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewLeases extends Component
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

    public Transaction $transaction; // Holds the current transaction for lease
    //public $transactions;
    public $leases = [];

    public $cannotDeleteItem = false;
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false; 
    public $statusFilter = ''; // Filter transactions by status

    //public declaration for bulk actions 
    public $selectedRows = []; 
    public $selectPageRows = false; 

    //Bulk Delete Method
    public function updatedSelectPageRows($value){
        //same with render
        if ($value) {
            $transactions = Transaction::with(['transactionUser', 'properties'])
                ->where('reservation_type_id', 1)
                ->when($this->search !== '', fn($query) => $query->where('first_name', 'like', '%' . $this->search . '%'))
                ->when($this->statusFilter !== '', fn($query) => $query->where('transaction_status', $this->statusFilter))
                ->orderBy($this->sortBy, $this->sortDir)
                ->paginate($this->perPage);
            
                //pluck ids for bulk delete
            $this->selectedRows = $transactions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    //Bulk Delete by getting ID
    public function deleteSelectedRows(){
        Transaction::whereIn('id', $this->selectedRows)->delete(); 
        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected leases got deleted!');
    }

    //Modal
    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true; 
    }

    //Modal
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    //Method to delete the lease
    public function deleteLease()
    {
        if ($this->confirmItemDelete) {
        $lease = Transaction::find($this->confirmItemDelete);

        if ($lease && in_array($lease->transaction_status, ['done', 'terminated'])) {
            $lease->delete();

            $fakeIDs = [];
            foreach (
                Transaction::where('reservation_type_id', 1) //house
                    ->orderBy('created_at', 'ASC')
                    ->get() as $index => $leaseItem
            ) {
                $fakeIDs[$leaseItem->id] = 'LEASE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }

            session(['fake_ids_leases' => $fakeIDs]);

            session()->flash('message', 'Lease successfully deleted!');
        } else {
            // Show modal instead of flash
            $this->cannotDeleteItem = true;
        }

        $this->confirmItemDelete = false;
    }
    }

    public function mount()
    {
        // Initializes session variable if not already set
        if (!session()->has('fake_ids_leases')) {
            session(['fake_ids_leases' => []]);
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
        $transactions = Transaction::with(['transactionUser', 'properties'])
            ->where('reservation_type_id', 1) // House reservation type
            ->when($this->search !== '', function ($query) {
            $search = '%' . $this->search . '%';
            $query->whereHas('transactionUser', function ($subQuery) use ($search) {
                $subQuery->where('first_name', 'like', $search)
                         ->orWhere('last_name', 'like', $search)
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search]);
            });
        })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('transaction_status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

            $leaseTransactions = Transaction::where('reservation_type_id', 1)->orderBy('created_at', 'ASC')->get();

            $fakeIDs = session('fake_ids_leases', []);

            if (count($fakeIDs) !== $leaseTransactions->count()) {
                $fakeIDs = [];
                foreach ($leaseTransactions as $index => $leaseItem) {
                    $fakeIDs[$leaseItem->id] = 'LEASE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }
                session(['fake_ids_leases' => $fakeIDs]);
            }
        return view('livewire.admin.properties.leases.view-leases', compact('transactions', 'fakeIDs'));
    }

}
