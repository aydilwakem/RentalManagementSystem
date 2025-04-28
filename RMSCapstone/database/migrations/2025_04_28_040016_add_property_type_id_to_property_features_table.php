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
        Schema::table('property_features', function (Blueprint $table) {
            $table->unsignedBigInteger('property_type_id')
              ->default(1) // Default 1 to prevent migration fail
              ->after('name'); 

        $table->foreign('property_type_id')
              ->references('id')
              ->on('property_types')
              ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_features', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropColumn('property_type_id');
        });
    }
};
