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
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('room_rate_id')->nullable()->after('property_id');

            $table->foreign('room_rate_id')
                ->references('id')
                ->on('property_rates')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropForeign(['room_rate_id']);
            $table->dropColumn('room_rate_id');
        });
    }
};
