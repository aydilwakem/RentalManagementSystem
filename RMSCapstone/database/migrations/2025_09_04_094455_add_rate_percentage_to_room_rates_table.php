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
        Schema::table('property_rates', function (Blueprint $table) {
            $table->decimal('rate_percentage', 5, 2)->nullable()->after('amount');
            // 5,2 means up to 999.99% (more than enough for your use case)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_rates', function (Blueprint $table) {
            $table->dropColumn('rate_percentage');
        });
    }
};
