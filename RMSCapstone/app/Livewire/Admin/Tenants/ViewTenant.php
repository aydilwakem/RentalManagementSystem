<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\TransactionUser;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewTenant extends Component
{
    // Public property to hold the tenant record
    public TransactionUser $tenant;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // Function to delete a tenant
    public function deleteTenant(TransactionUser $tenant)
    {
        if (!$tenant) {
            session()->flash('error', 'Tenant not found!');
            return;
        }

        // Delete the tenant
        if ($this->confirmItemDelete) {
            $tenant->delete();
            $this->confirmItemDelete = false;

            // Flash success message
            session()->flash('message', 'Tenant successfully deleted!');

            // Redirect to the admin tenants page
            return redirect()->route('admin.tenants');
        }
    }

    public function render()
    {
        return view('livewire.admin.tenants.view-tenant');
    }
}
