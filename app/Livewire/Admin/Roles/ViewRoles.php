<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ViewRoles extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDir = 'ASC';
    public $search = '';
    public $perPage = 10;

    public $confirmItemDelete = false;

    //mount session for fake IDs
    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_roles')) {
            session(['fake_ids_roles' => []]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteRole()
    {
        $role = Role::find($this->confirmItemDelete);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        // Ensure permissions exist before detaching
        if ($role->permissions()->exists()) {
            $role->permissions()->detach();
        }

        // Delete the role
        if ($role) {
            if ($this->confirmItemDelete) {
                Role::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

                // Fetch remaining roles - sorted by creation date
                $roles = Role::orderBy('created_at', 'ASC')->get();

                // Reset fake IDs
                $fakeIDs = [];
                foreach ($roles as $index => $roleItem) {
                    $fakeIDs[$roleItem->id] = 'ROLE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                // Store updated fake IDs in a unique session key
                session(['fake_ids_roles' => $fakeIDs]);

                session()->flash('message', 'Role successfully deleted!');
            }
        }
    }


    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
    {
        // For displaying roles (with search, sort, pagination)
        $roles = Role::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // For generating fake IDs (always by created_at ASC, static)
        $allRoles = Role::orderBy('created_at', 'ASC')->get();

        // Calculate fake IDs
        $fakeIDs = session('fake_ids_roles', []);
        if (count($fakeIDs) !== $allRoles->count()) {
            $fakeIDs = [];
            foreach ($allRoles as $index => $roleItem) {
                $fakeIDs[$roleItem->id] = 'ROLE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_roles' => $fakeIDs]);
        }

        return view('livewire.admin.roles.view-roles', compact('roles', 'fakeIDs'));
    }
}
