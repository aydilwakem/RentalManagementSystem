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
    public $perPage = 5;

    public $confirmItemDelete = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);

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
        $roles = Role::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.roles.view-roles', compact('roles'));
    }
}
