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
            $table->enum('heard_from', ['Facebook', 'Instagram', 'TikTok', 'YouTube', 'Google'])->nullable()->after('isConfirmed');
            $table->enum('reservation_source', ['Airbnb', 'WebApp', 'Phone', 'Messenger', 'Other'])->nullable()->after('heard_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->dropColumn('heard_from');
            $table->dropColumn('reservation_source');
        });
    }
};
