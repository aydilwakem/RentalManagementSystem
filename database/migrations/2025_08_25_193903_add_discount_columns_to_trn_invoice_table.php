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
        Schema::table('trn_invoice', function (Blueprint $table) {
            $table->decimal('base_subtotal', 10, 2)->after('sub_total')->default(0);
            $table->decimal('total_discount', 10, 2)->after('base_subtotal')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_invoice', function (Blueprint $table) {
            $table->dropColumn(['base_subtotal', 'total_discount']);
        });
    }
};
