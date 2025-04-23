<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add unique constraint and new column
        Schema::table('trn_invoice', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('payment_status');
        });

        // Modify enum by recreating it
        DB::statement("ALTER TABLE trn_invoice MODIFY COLUMN payment_status ENUM('pending', 'paid', 'partially paid', 'failed', 'overdue') NOT NULL");
    }

    public function down(): void
    {
        // Reverse changes
        Schema::table('trn_invoice', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });

        DB::statement("ALTER TABLE trn_invoice MODIFY COLUMN payment_status ENUM('pending', 'paid', 'partially paid', 'failed') NOT NULL");
    }
};
