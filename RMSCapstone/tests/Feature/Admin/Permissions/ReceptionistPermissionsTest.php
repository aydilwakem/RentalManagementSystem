<?php

namespace Tests\Feature\Admin\Permissions;


use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReceptionistPermissionsTest extends TestCase
{
   protected $receptionistPermissions = [
        // Room Management
        'room-list',
        'room-create',
        'room-edit',
        'room-delete',
        'room-view',
        'room-soft-delete',

        // Room Categories
        'room-category-list',
        'room-category-create',
        'room-category-edit',
        'room-category-delete',
        'room-category-view',
        'room-category-soft-delete',

        // Room Rates
        'room-rate-list',
        'room-rate-create',
        'room-rate-edit',
        'room-rate-delete',
        'room-rate-view',
        'room-rate-soft-delete',

        // Amenities
        'amenity-list',
        'amenity-create',
        'amenity-edit',
        'amenity-delete',
        'amenity-view',
        'amenity-soft-delete',

        // New Reservations
        'new-reservation-list',
        'new-reservation-create',
        'new-reservation-edit',
        'new-reservation-view',
        'new-reservation-delete',
        'new-reservation-confirm-receipt',
        'new-reservation-confirm',
        'new-reservation-soft-delete',

        // Confirmed Reservations
        'confirmed-reservation-list',
        'confirmed-reservation-view',
        'confirmed-reservation-edit',
        'confirmed-reservation-delete',

        // On-going Bookings
        'on-going-booking-list',
        'on-going-booking-view',
        'on-going-booking-edit',
        'on-going-booking-delete',

        // Old Bookings
        'old-booking-list',
        'old-booking-view',
        'old-booking-delete',
        'old-booking-soft-delete',
    ];

    public function test_receptionist_role_has_all_expected_permissions()
    {
        $role = Role::firstOrCreate(['name' => 'Receptionist']);

        foreach ($this->receptionistPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }

            $this->assertTrue(
                $role->hasPermissionTo($permission),
                "Receptionist role is missing permission: {$permissionName}"
            );
        }
    }
}
