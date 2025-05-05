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

    public function mount()
    {
        $this->permissions = Permission::all(); // Fetch all permissions
    }

    public function saveRole()
    {

        $this->validate([
            'name' => 'required|string|min:3|unique:roles,name',
            'selectedPermissions' => 'array|min:1',
        ]);


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
    // Group the permissions dynamically based on the permission name pattern
    $groupedPermissions = $this->permissions->groupBy(function ($permission) {
        // Group permissions by their category based on the naming convention
        if (str_contains($permission->name, 'room-')) {
            return 'Rooms';
        } elseif (str_contains($permission->name, 'event-')) {
            return 'Events';
        } elseif (str_contains($permission->name, 'amenity-')) {
            return 'Amenities';
        } elseif (str_contains($permission->name, 'activity-')) {
            return 'Activities';
        } elseif (str_contains($permission->name, 'house-')) {
            return 'House';
        } elseif (str_contains($permission->name, 'setting-')) {
            return 'Settings';
        } else {
            return 'Others';  // Default category for anything that doesn't match
        }
    });

    return view('livewire.admin.roles.create-role', [
        'groupedPermissions' => $groupedPermissions,
    ]);
}

}
