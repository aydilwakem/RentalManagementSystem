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
            "event-inclusions-create",
            "event-inclusions-view",
            "event-inclusions-edit",
            "event-inclusions-delete",
            "event-inclusions-soft-delete",

            "house-features-create",
            "house-features-view",
            "house-features-edit",
            "house-features-delete",
            "house-features-soft-delete",

            "leases-create",
            "leases-view",
            "leases-edit",
            "leases-delete",
            "leases-soft-delete",

            "lease-reports",
            "reservation-reports",
        ];

        // Run this using 
        // php artisan db:seed --class=PermissionSeeder



        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
