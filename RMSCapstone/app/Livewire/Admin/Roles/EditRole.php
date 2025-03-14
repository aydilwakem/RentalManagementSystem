<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


#[Layout('layouts.app')]
class EditRole extends Component
{
    public Role $role;
    public $name;
    public $permissions = [];
    public $selectedPermissions = [];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;

        // Fetch all permissions
        $this->permissions = Permission::all();
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function updateRole()
    {
        $this->validate([
            'name' => 'nullable|string|min:3|unique:roles,name,' . $this->role->id,
        ]);

        // Update role details
        $this->role->update([
            'name' => $this->name,
        ]);

        // Update permissions
        $this->role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Role updated successfully!');

        return redirect()->route('admin.manage-users');
    }



    public function render()
    {
        return view('livewire.admin.roles.edit-role');
    }
}
