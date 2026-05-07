<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Transaction;
use App\Models\TransactionUser;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewTenant extends Component
{
    // Public property to hold the tenant record
    public TransactionUser $tenant;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function to delete a tenant
    public function deleteTenant()
    {
        if ($this->confirmItemDelete) {
        $tenant = TransactionUser::find($this->confirmItemDelete);

        if (!$tenant) {
            session()->flash('error', 'Tenant not found!');
            return redirect()->route('admin.tenants');
        }

        // Check if the event hall is linked to any transaction
        $usedInTransactions = Transaction::whereHas('transactionUser', function ($query) use ($tenant) {
            $query->where('created_by', $tenant->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete because hall is active in Transactions
            $this->confirmItemDelete = null;
            return;
        }

        // Delete the hall
        $tenant->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Tenant successfully deleted!');
        }

        return redirect()->route('admin.tenants');
    }

    public function render()
    {
        return view('livewire.admin.tenants.view-tenant');
    }
}
