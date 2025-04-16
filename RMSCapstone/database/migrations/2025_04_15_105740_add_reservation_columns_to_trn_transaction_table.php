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
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_type_id')->after('id');
            $table->unsignedBigInteger('reservation_id')->after('reservation_type_id');

            // Foreign Key Constraint
            $table->foreign('reservation_type_id')->references('id')->on('trn_reservation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropForeign(['reservation_type_id']);
            $table->dropColumn(['reservation_type_id', 'reservation_id']);
        });
    }
};
