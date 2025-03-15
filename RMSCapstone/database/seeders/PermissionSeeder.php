<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // // Activity
            // "activity-view",
            // "activity-list",
            // "activity-create",
            // "activity-edit",
            // "activity-delete",

            // // Event Category
            // "event-category-view",
            // "event-category-list",
            // "event-category-create",
            // "event-category-edit",
            // "event-category-delete",

            // // Event Hall
            // "event-hall-view",
            // "event-hall-list",
            // "event-hall-create",
            // "event-hall-edit",
            // "event-hall-delete",

            // // Event
            // "event-view",
            // "event-list",
            // "event-create",
            // "event-edit",
            // "event-delete",

            // // Maintenance
            // "maintenance-view",
            // "maintenance-list",
            // "maintenance-create",
            // "maintenance-edit",
            // "maintenance-delete",

            // // Role
            // "role-view",
            // "role-list",
            // "role-create",
            // "role-edit",
            // "role-delete",

            // // Setting Payment Method
            // "payment-method-view",
            // "payment-method-list",
            // "payment-method-create",
            // "payment-method-edit",
            // "payment-method-delete",

            // // User
            // "user-view",
            // "user-list",
            // "user-create",
            // "user-edit",
            // "user-delete",

            // // Dashboard
            // "dashboard-view"

            'room-list',
            'room-view',
            'room-create',
            'room-edit',
            'room-delete',

            'amenity-list',
            'amenity-view',
            'amenity-create',
            'amenity-edit',
            'amenity-delete',

            'room-rate-list',
            'room-rate-view',
            'room-rate-create',
            'room-rate-edit',
            'room-rate-delete',

            'room-category-list',
            'room-category-view',
            'room-category-create',
            'room-category-edit',
            'room-category-delete',
        ];

        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
