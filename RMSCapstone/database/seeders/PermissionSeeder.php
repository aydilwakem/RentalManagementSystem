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
            "leases-list",
            "house-features-list",
            "event-inclusions-list",
            "event-reports",
            "settings",
            "reports",
        ];

        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
