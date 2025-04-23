<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRoomIdFromPrdRoomRates extends Migration
{
    public function up()
    {
        // Drop the foreign key constraint first
        Schema::table('prd_room_rates', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign('prd_room_rates_room_id_foreign');
        });

        // Then, drop the room_id column
        Schema::table('prd_room_rates', function (Blueprint $table) {
            $table->dropColumn('room_id');
        });
    }

    public function down()
    {
        // Optional: Add back the room_id column if the migration is rolled back
        Schema::table('prd_room_rates', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->nullable(); // Adjust this if the column is required to be non-nullable
            // Add back the foreign key constraint if needed
            $table->foreign('room_id')->references('id')->on('prd_rooms')->onDelete('cascade');
        });
    }
}
