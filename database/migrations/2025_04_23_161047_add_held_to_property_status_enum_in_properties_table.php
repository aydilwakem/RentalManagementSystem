<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeldToPropertyStatusEnumInPropertiesTable extends Migration
{
    public function up()
    {
        // Modify the 'property_status' enum column to add 'Held'
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('property_status', ['available', 'booked', 'out_of_service', 'held'])
                ->default('available') // Optional: set default status to 'Available'
                ->change();
        });
    }

    public function down()
    {
        // Rollback the enum changes and remove 'Held'
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('property_status', ['available', 'booked', 'out_of_service'])
                ->default('available') // Optional: set default status to 'Available'
                ->change();
        });
    }
}
