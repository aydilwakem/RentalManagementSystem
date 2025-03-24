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
        Schema::table('trn_residents', function (Blueprint $table) {
            // Add the new column
            $table->string('origin')->after('residency_status');

            // Remove old columns
            $table->dropColumn(['residency', 'country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_residents', function (Blueprint $table) {
            // Add the old columns back
            $table->string('residency')->nullable();
            $table->string('country')->nullable();

            // Remove the new column
            $table->dropColumn('origin');
        });
    }
};
