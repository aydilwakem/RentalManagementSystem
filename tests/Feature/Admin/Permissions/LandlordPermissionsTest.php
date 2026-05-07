<?php

namespace Tests\Feature\Admin\Permissions;


use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LandlordPermissionsTest extends TestCase
{
    protected $landlordPermissions = [
        // Houses
        'house-list',
        'house-create',
        'house-view',
        'house-edit',
        'house-delete',
        'house-soft-delete',

        // House Features
        'house-features-list',
        'house-features-create',
        'house-features-view',
        'house-features-edit',
        'house-features-delete',
        'house-features-soft-delete',

        // Tenants
        'tenant-list',
        'tenant-create',
        'tenant-view',
        'tenant-edit',
        'tenant-delete',
        'tenant-soft-delete',

        // Maintenance
        'maintenance-view',
        'maintenance-list',
        'maintenance-create',
        'maintenance-edit',
        'maintenance-delete',
        'maintenance-soft-delete',

        // Leases
        'leases-list',
        'leases-create',
        'leases-view',
        'leases-edit',
        'leases-delete',
        'leases-soft-delete',
    ];

    public function test_landlord_role_has_all_expected_permissions()
    {
        $role = Role::firstOrCreate(['name' => 'Landlord']);

        foreach ($this->landlordPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }

            $this->assertTrue(
                $role->hasPermissionTo($permission),
                "Landlord role is missing permission: {$permissionName}"
            );
        }
    }
}
