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
            $table->unsignedBigInteger('property_id')->nullable()->after('reservation_type_id'); // adjust 'after' if needed

            $table->foreign('property_id')
                ->references('id') // or 'id' if that's your PK in properties table
                ->on('properties')
                ->onDelete('cascade'); // or 'cascade', 'restrict', etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
        });
    }
};
