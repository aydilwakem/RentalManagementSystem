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
        Schema::table('trn_invoice', function (Blueprint $table) {
            // Add new columns
            $table->decimal('sub_total', 10, 2)->default(0.00)->after('invoice_type');
            $table->decimal('deposit_paid', 10, 2)->default(0.00)->after('sub_total');
            $table->decimal('amount_paid', 10, 2)->default(0.00)->after('deposit_paid');
            $table->decimal('balance_due', 10, 2)->default(0.00)->after('amount_paid');
            $table->date('due_date')->after('balance_due');
            $table->enum('invoice_status', ['pending', 'completed_at', 'failed', 'overdue'])->default('pending')->after('due_date');
            $table->dateTime('completed_at')->nullable()->after('invoice_status');

            // Remove old columns
            $table->dropColumn('payment_status');
            $table->dropColumn('total_amount');
            $table->dropColumn('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trn_invoice', function (Blueprint $table) {
            // Revert the changes
            $table->dropColumn('sub_total');
            $table->dropColumn('deposit_paid');
            $table->dropColumn('amount_paid');
            $table->dropColumn('balance_due');
            $table->dropColumn('due_date');
            $table->dropColumn('invoice_status');
            $table->dropColumn('completed_at');

            // Re-add old columns
            $table->string('payment_status')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->dateTime('paid_at')->nullable();
        });
    }
};
