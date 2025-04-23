<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\TransactionUser;
use Livewire\Component;

class DeletedTenants extends Component
{
    public $deletedTenants;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedTenants();
    }

    public function fetchDeletedTenants()
    {
        $this->deletedTenants = TransactionUser::onlyTrashed()
            ->where('trn_user_type', 'tenant')
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    public function restoreTenant($tenantId)
    {
        $tenant = TransactionUser::withTrashed()->find($tenantId);
        if ($tenant) {
            $tenant->restore(); // Restore 
            session()->flash('message', 'Tenant restored successfully.');
            $this->fetchDeletedTenants();
        }
    }

    public function deleteTenantForever($tenantId)
    {
        $tenant = TransactionUser::withTrashed()->find($this->confirmItemDelete);
        if ($tenant) {
            $tenant->forceDelete(); // Permanently delete the tenant
            session()->flash('message', 'Tenant permanently deleted.');
            $this->fetchDeletedTenants();
        }
        $this->confirmItemDelete = false;
    }


    public function render()
    {
        // Generate fake IDs for deleted room rates
        $fakeIDs = session('fake_ids_tenants', []);

        $deletedIds = $this->deletedTenants->pluck('id')->toArray();

        // Refresh fake IDs if mismatch or count changes
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedTenants as $index => $tenant) {
                $fakeIDs[$tenant->id] = 'TNT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_tenants' => $fakeIDs]);
        }

        return view('livewire.admin.tenants.deleted-tenants', [
            'deletedTenants' => $this->deletedTenants,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
