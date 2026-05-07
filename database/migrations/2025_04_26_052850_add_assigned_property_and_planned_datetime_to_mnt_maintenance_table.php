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
            $table->unsignedBigInteger('property_id')->default(1)->after('priority_status');
            $table->dateTime('planned_datetime')->nullable()->after('property_id');

        $table->foreign('property_id')
          ->references('id')
          ->on('properties')
          ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnt_maintenance', function (Blueprint $table) {
        // Drop the foreign key constraint first
        $table->dropForeign(['property_id']);

        // Drop the columns added in the up() method
        $table->dropColumn(['property_id', 'planned_datetime']);
        });
    }
};
