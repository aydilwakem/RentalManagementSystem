<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mnt_maintenance', function (Blueprint $table) {
            $table->json('maintenance_images')->nullable()->after('planned_datetime');
            $table->json('resolved_images')->nullable()->after('maintenance_images');
            $table->dateTime('routine_datetime')->nullable()->after('resolved_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnt_maintenance', function (Blueprint $table) {
            $table->dropColumn(['maintenance_images', 'resolved_images', 'routine_datetime']);
        });
    }
};
