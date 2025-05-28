<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->string('transaction_number')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropUnique(['transaction_number']);
            $table->dropColumn('transaction_number');
        });
    }
};
