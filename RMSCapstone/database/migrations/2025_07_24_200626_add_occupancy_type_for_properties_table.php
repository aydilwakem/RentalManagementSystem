<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedInteger('max_guests')->nullable()->after('max_kids');; // For 'whole_number'
            $table->enum('occupancy_type', ['combinations', 'whole_number', 'ideal_guest'])->default('whole_number')->after('max_guests');
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['occupancy_type', 'max_guests']);
        });
    }
};
