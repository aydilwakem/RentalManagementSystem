<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('st_settings', function (Blueprint $table) {
            // Remove the old column
            $table->dropColumn('payment_proof_expiration_hours');
            
            // Add new columns for each reservation type
            $table->integer('room_payment_proof_expiration_hours')->default(24);
            $table->integer('event_payment_proof_expiration_hours')->default(24);
            $table->integer('day_tour_payment_proof_expiration_hours')->default(24);
        });
    }

    public function down()
    {
        Schema::table('st_settings', function (Blueprint $table) {
            // Add back the old column
            $table->integer('payment_proof_expiration_hours')->default(24);
            
            // Remove the new columns
            $table->dropColumn([
                'room_payment_proof_expiration_hours',
                'event_payment_proof_expiration_hours',
                'day_tour_payment_proof_expiration_hours'
            ]);
        });
    }
};