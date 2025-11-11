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
            //DayTour Reports
           "daytour-reports", 

            //Archives
            "archive-events-view", 
            "archive-reservation-view", 
            "archive-daytour-view", 
            "archive-leases-view", 



            //Daytours
            // "daytour-list",
            // "daytour-create", 
            // "daytour-view", 
            // "daytour-edit", 
            // "daytour-soft-delete", 

            //Daytour Rates
            // "daytourrate-list", 
            // "daytourrate-create", 
            // "daytourrate-view", 
            // "daytourrate-edit", 
            // "daytourrate-soft-delete", 

            //Daytour reservations
            // "daytour-reservation-list", 
            // "daytour-reservation-view", 
            



            //Backup
            // "backup-create",
            // "backup-delete",
            // "backup-download",
            // "backup-view",
            
            //Services
            // "service-list",
            // "service-create",
            // "service-edit",
            // "service-view",
            // "service-soft-delete",

            //Activity Logs
            // "activity-logs-view",

            // //Promo Codes
            // "promo-code-list",
            // "promo-code-create",
            // "promo-code-edit",
            // "promo-code-view",
            // "promo-code-soft-delete",
        ];

        // Run this using 
        // php artisan db:seed --class=PermissionSeeder



        foreach ($permissions as $key => $permission) {
            $permission = Permission::create(['name' => $permission]);
        }
    }
}
