<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropHouseCategoriesAndPrdAmenitiesTables extends Migration
{
    public function up()
    {

        // Drop the tables
        Schema::dropIfExists('house_categories');
        Schema::dropIfExists('prd_amenities');
    }

    public function down()
    {
        // Recreate the tables in case of rollback (optional)

        // Recreate house_categories table
        Schema::create('house_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Recreate prd_amenities table
        Schema::create('prd_amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
}
