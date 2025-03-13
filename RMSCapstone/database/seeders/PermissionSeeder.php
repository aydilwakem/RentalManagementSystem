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
            // Room
            "room-list",
            "room-create",
            "room-edit",
            "room-delete",

            // Room Category
            "room-category-list",
            "room-category-create",
            "room-category-edit",
            "room-category-delete",

            // Room Rate
            "room-rate-list",
            "room-rate-create",
            "room-rate-edit",
            "room-rate-delete",

            // Amenities
            "amenity-list",
            "amenity-create",
            "amenity-edit",
            "amenity-delete",
        ];

        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
