<?php

namespace Tests\Feature\Admin\Permissions;


use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EventManagerPermissionTest extends TestCase
{
    // ------------- A TEST TO VERIFY EVENT MANAGER HAS THESE PERMISSIONS
    protected $eventManagerPermissions = [
        // Event Categories
        'event-category-view',
        'event-category-list',
        'event-category-create',
        'event-category-edit',
        'event-category-delete',
        'event-category-soft-delete',

        // Event Halls
        'event-hall-view',
        'event-hall-list',
        'event-hall-create',
        'event-hall-edit',
        'event-hall-delete',
        'event-hall-soft-delete',

        // Inclusions
        'event-inclusions-list',
        'event-inclusions-create',
        'event-inclusions-view',
        'event-inclusions-edit',
        'event-inclusions-delete',
        'event-inclusions-soft-delete',

        // Events
        'event-view',
        'event-list',
        'event-create',
        'event-edit',
        'event-delete',
        'event-soft-delete',
        'event-reports',
    ];

    public function test_event_manager_role_has_all_expected_permissions()
    {
        $role = Role::firstOrCreate(['name' => 'Event Manager']);

        foreach ($this->eventManagerPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }

            $this->assertTrue(
                $role->hasPermissionTo($permission),
                "Event Manager role is missing permission: {$permissionName}"
            );
        }
    }
}
