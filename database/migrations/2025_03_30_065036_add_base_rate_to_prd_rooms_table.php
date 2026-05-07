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
        Schema::table('prd_rooms', function (Blueprint $table) {
            $table->decimal('base_rate', 10, 2)->after('room_status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('prd_rooms', function (Blueprint $table) {
            $table->dropColumn('base_rate'); // Removes 'base_rate' if rolled back
        });
    }
};
