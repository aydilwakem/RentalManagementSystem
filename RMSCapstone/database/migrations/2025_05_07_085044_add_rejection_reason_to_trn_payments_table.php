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
            $table->enum('rejection_reason', [
                'Incomplete details',
                'Invalid receipt',
                'Mismatched amount',
                'Duplicate payment',
                'Suspicious activity',
                'Other',
            ])->nullable()->after('payment_status');
        });
    }

    public function down()
    {
        Schema::table('trn_payments', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
