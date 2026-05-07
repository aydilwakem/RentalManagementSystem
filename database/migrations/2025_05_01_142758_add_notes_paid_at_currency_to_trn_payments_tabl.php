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
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->string('currency', 10)->default('PHP')->after('notes');
            $table->dateTime('paid_at')->nullable()->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->dropColumn(['currency', 'paid_at']);
        });
    }
};
