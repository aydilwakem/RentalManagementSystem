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
            "room-soft-delete",
            "room-category-soft-delete",
            "room-rate-soft-delete",
            "amenity-soft-delete",
            "activity-soft-delete",
            "event-soft-delete",
            "event-category-soft-delete",
            "event-hall-soft-delete",
            "maintenance-soft-delete",
            "payment-method-soft-delete",
            "branding-view",
            "individual-room-rate-create",
            "individual-room-rate-edit",
        ];

        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
