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
            // Drop JSON column
            $table->dropColumn('special_requests');

            // Add new reply field
            $table->text('request_reply')->nullable()->after('requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            // Rollback: add back special_requests
            $table->json('special_requests')->nullable()->after('requests');

            // Remove request_reply
            $table->dropColumn('request_reply');
        });
    }
};
