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
        Schema::create('trn_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('receipt_number')->unique();
            $table->decimal('amount_received', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamp('receipt_date')->useCurrent();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('invoice_id')->references('id')->on('trn_invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trn_receipts');
    }
};
