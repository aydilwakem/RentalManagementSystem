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
        Schema::table('trn_feedback', function (Blueprint $table) {
            $table->integer('feedback_likes')->default(0);
            $table->integer('feedback_dislikes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_feedback', function (Blueprint $table) {
            $table->dropColumn(['feedback_likes', 'feedback_dislikes']);
        });
    }
};
