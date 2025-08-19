<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prd_services', function (Blueprint $table) {
            // Make 'amount' nullable
            $table->decimal('amount', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('prd_services', function (Blueprint $table) {
            // Revert back to NOT NULL with a default value (e.g., 0)
            $table->decimal('amount', 10, 2)->nullable(false)->default(0)->change();
        });
    }
};
