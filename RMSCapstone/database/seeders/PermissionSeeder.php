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

            // Setting Payment Method
            "payment-method-view",
            "payment-method-list",
            "payment-method-create",
            "payment-method-edit",
            "payment-method-delete",

            // User
            "user-view",
            "user-list",
            "user-create",
            "user-edit",
            "user-delete",

            // Dashboard
            "dashboard-view"
        ];

        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
