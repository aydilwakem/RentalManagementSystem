<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, you need to modify the enum directly using raw SQL
        DB::statement("ALTER TABLE `prd_services` MODIFY `type` ENUM('addon','penalty','package','food','merchandise') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the new enum values in case of rollback
        DB::statement("ALTER TABLE `prd_services` MODIFY `type` ENUM('addon','penalty','package') NOT NULL");
    }
};
