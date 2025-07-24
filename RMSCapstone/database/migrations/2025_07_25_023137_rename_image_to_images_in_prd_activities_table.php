<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prd_activities', function (Blueprint $table) {
            $table->renameColumn('image', 'images');
            $table->json('images')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prd_activities', function (Blueprint $table) {
            $table->renameColumn('images', 'image');
            $table->string('images')->change();
        });
    }
};
