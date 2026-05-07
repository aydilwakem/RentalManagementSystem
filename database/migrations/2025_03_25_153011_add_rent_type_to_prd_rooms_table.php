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
        Schema::table('prd_rooms', function (Blueprint $table) {
            $table->enum('rent_type', ['short-term', 'long-term'])->default('long-term')->after('room_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prd_rooms', function (Blueprint $table) {
            $table->dropColumn('rent_type');
        });
    }
};
