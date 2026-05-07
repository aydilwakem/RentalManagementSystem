<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->decimal('convenience_fee', 10, 2)->nullable()->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->dropColumn(['convenience_fee']);
        });
    }
};
