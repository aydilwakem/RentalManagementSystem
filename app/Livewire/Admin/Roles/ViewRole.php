<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
class ViewRole extends Component
{
    public role $role;

    public $confirmItemDelete = false;
    public $selectedItemId = null;

    public function confirmDelete($id)
    {
        $this->selectedItemId = $id;
        $this->confirmItemDelete = true;
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

        if ($this->confirmItemDelete) {
            $role->delete();
            $this->confirmItemDelete = false;
            $this->selectedItemId = null;

            session()->flash('message', 'Role successfully deleted!');

            // Redirect to the admin room categories page
            return redirect()->route('admin.manage-users');
        }
    }

    public function render()
    {
        return view('livewire.admin.roles.view-role', [
            'role' => $this->role,
            'rolePermissions' => $this->role->permissions->pluck('name')->toArray(), // Only this role's permissions as strings
        ]);
    }
}
