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
        Schema::table('st_settings', function (Blueprint $table) {
            $table->integer('payment_proof_expiration_hours')->default(24)->after('deposit_percentage');
        });
    }

    public function down()
    {
        Schema::table('st_settings', function (Blueprint $table) {
            $table->dropColumn('payment_proof_expiration_hours');
        });
    }
};
