<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->decimal('convenience_fee', 10, 2)->nullable()->after('total_amount');
            $table->decimal('sub_total', 10, 2)->nullable()->after('convenience_fee');
        });
    }

    public function down()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropColumn(['convenience_fee', 'sub_total']);
        });
    }
};
