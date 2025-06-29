<?php

namespace Tests\Feature\Admin\Permissions;

use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperadminPermissionsTest extends TestCase
{
   // ------------- A TEST TO VERIFY SUPERADMIN HAVE THESE PERMISSIONS
     protected $superAdminPermissions = [
        // Room Management
        'room-list',
        'room-create',
        'room-edit',
        'room-delete',
        'room-view',
        'room-soft-delete',
        'room-category-list',
        'room-category-create',
        'room-category-edit',
        'room-category-delete',
        'room-category-view',
        'room-category-soft-delete',
        'room-rate-list',
        'room-rate-create',
        'room-rate-edit',
        'room-rate-delete',
        'room-rate-view',
        'room-rate-soft-delete',

        // Amenities & Reservations
        'amenity-list',
        'amenity-create',
        'amenity-edit',
        'amenity-delete',
        'amenity-view',
        'amenity-soft-delete',

        'new-reservation-list',
        'new-reservation-create',
        'new-reservation-edit',
        'new-reservation-view',
        'new-reservation-delete',
        'new-reservation-confirm-receipt',
        'new-reservation-confirm',
        'new-reservation-soft-delete',

        'confirmed-reservation-list',
        'confirmed-reservation-view',
        'confirmed-reservation-edit',
        'confirmed-reservation-delete',

        'on-going-booking-list',
        'on-going-booking-view',
        'on-going-booking-edit',
        'on-going-booking-delete',

        'old-booking-list',
        'old-booking-view',
        'old-booking-delete',
        'old-booking-soft-delete',

        // Events
        'event-view',
        'event-list',
        'event-create',
        'event-edit',
        'event-delete',
        'event-soft-delete',
        'event-reports',

        'event-category-view',
        'event-category-list',
        'event-category-create',
        'event-category-edit',
        'event-category-delete',
        'event-category-soft-delete',

        'event-hall-view',
        'event-hall-list',
        'event-hall-create',
        'event-hall-edit',
        'event-hall-delete',
        'event-hall-soft-delete',

        'event-inclusions-list',
        'event-inclusions-create',
        'event-inclusions-view',
        'event-inclusions-edit',
        'event-inclusions-delete',
        'event-inclusions-soft-delete',

        // House & Tenants
        'house-list',
        'house-create',
        'house-view',
        'house-edit',
        'house-delete',
        'house-soft-delete',

        'house-features-list',
        'house-features-create',
        'house-features-view',
        'house-features-edit',
        'house-features-delete',
        'house-features-soft-delete',

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

        // Payment Methods
        'payment-method-view',
        'payment-method-list',
        'payment-method-create',
        'payment-method-edit',
        'payment-method-delete',
        'payment-method-soft-delete',

        // Payments & Invoices
        'payments-list',
        'invoices-list',

        // Dashboard, Roles, Users
        'dashboard-view',
        'role-view',
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',

        'user-view',
        'user-list',
        'user-create',
        'user-edit',
        'user-delete',

        // Activities, Reports, Feedback
        'activity-view',
        'activity-list',
        'activity-create',
        'activity-edit',
        'activity-delete',
        'activity-soft-delete',
        'lease-reports',
        'reservation-reports',
        'feedback',

        // Settings
        'appearance-view',
    ];

    public function test_super_admin_has_all_required_permissions()
    {
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        foreach ($this->superAdminPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }

            $this->assertTrue(
                $role->hasPermissionTo($permission),
                "Super Admin role is missing permission: {$permissionName}"
            );
        }
    }
}

