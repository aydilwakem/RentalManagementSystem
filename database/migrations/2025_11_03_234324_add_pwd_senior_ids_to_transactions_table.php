<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->json('pwd_senior_ids')->nullable()->after('promo_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropColumn('pwd_senior_ids');
        });
    }

};
