<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConnectPropertyFeaturesToPropertyTypes extends Migration
{
    public function up()
    {
        // Add property_type_id column to the property_features table
        Schema::table('property_features', function (Blueprint $table) {
            $table->unsignedBigInteger('property_type_id')->nullable()->after('id');  // You can set it nullable or not based on your requirements
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade'); // Cascade delete if the property_type is deleted
        });
    }

    public function down()
    {
        // Reverse the changes in case of rollback
        Schema::table('property_features', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropColumn('property_type_id');
        });
    }
}
