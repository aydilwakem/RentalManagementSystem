<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_vouchers', function (Blueprint $table) {
            $table->id();

            // Foreign key to trn_transactions
            $table->foreignId('transaction_id')
                ->constrained('trn_transactions')
                ->onDelete('cascade');

            // Voucher type enum
            $table->enum('voucher_type', ['Food', 'Other'])
                ->default('Food');

            // Voucher amount
            $table->decimal('voucher_amount', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_vouchers');
    }
};
