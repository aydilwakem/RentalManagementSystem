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
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('total_amount');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->text('remarks')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_properties', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_at', 'remarks']);
        });
    }
};
