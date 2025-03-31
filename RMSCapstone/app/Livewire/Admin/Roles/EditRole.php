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

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function isSuperAdmin()
    {
        return $this->role->name === 'Super Admin' 
        || $this->role->name === 'superadmin'; 
    }

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
        try{
        $this->validate([
            'name' => 'nullable|string|min:3|unique:roles,name,' . $this->role->id,
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

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
