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
        Schema::table('trn_invoice_discounts', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('discount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_invoice_discounts', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
