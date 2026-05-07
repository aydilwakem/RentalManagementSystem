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
        Schema::table('st_settings', function (Blueprint $table) {
            $table->boolean('enable_deposit_percentage')->default(true)->after('deposit_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('st_settings', function (Blueprint $table) {
            $table->dropColumn('enable_deposit_percentage');
        });
    }
};
