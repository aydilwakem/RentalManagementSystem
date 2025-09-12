<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $name; // Store role name
    public $selectedPermissions = []; // Store selected permissions
    public $permissions = []; // Store all available permissions

    public $confirmCreateItem = false; //Create modal

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        $this->permissions = Permission::all(); // Fetch all permissions
    }

    public function saveRole()
    {
        try{
            $this->validate([
            'name' => 'required|string|min:3|regex:/^[A-Za-z\s\-]+$/|unique:roles,name',
            'selectedPermissions' => 'array|min:1',
        ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create role with correct guard
        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
        ]);

        // Fetch permission names using their IDs
        $permissionNames = Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();

        // Assign permissions using names
        $role->syncPermissions($permissionNames);

        // Reset form fields
        $this->reset('name', 'selectedPermissions');

        session()->flash('message', 'Role successfully created!');

        return redirect()->route('admin.manage-users');
    }

    public function render()
    {
        return view('livewire.admin.roles.create-role');
    }
}
