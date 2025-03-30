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

            // New Reservations
            "new-reservation-list",
            "new-reservation-create",
            "new-reservation-edit",
            "new-reservation-view",
            "new-reservation-delete",
            "new-reservation-confirm-receipt",
            "new-reservation-confirm",
            "new-reservation-soft-delete",

            // Confirmed Reservations
            "confirmed-reservation-list",
            "confirmed-reservation-view",
            "confirmed-reservation-edit",
            "confirmed-reservation-delete",

            // On-Going Bookings
            "on-going-booking-list",
            "on-going-booking-view",
            "on-going-booking-edit",
            "on-going-booking-delete",

            // Old Bookings
            "old-booking-list",
            "old-booking-view",
            "old-booking-delete",
            "old-booking-soft-delete",

            // Houses
            "house-list",
            "house-create",
            "house-view",
            "house-edit",
            "house-delete",
            "house-soft-delete",

            // House Categories
            "house-category-list",
            "house-category-create",
            "house-category-view",
            "house-category-edit",
            "house-category-delete",
            "house-category-soft-delete",

            // Tenants
            "tenant-list",
            "tenant-create",
            "tenant-view",
            "tenant-edit",
            "tenant-delete",
            "tenant-soft-delete",

            // Appearance
            "appearance-view",


        ];

        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
