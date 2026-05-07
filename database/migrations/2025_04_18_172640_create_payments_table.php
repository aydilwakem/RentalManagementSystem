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
        Schema::create('trn_payments', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('payment_method_id');

            // Payment Info
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_type', [
                'Room Rent',
                'House Rent',
                'Activity Fee',
                'Event Hall',
                'Event Package',
                'Security Deposit',
            ])->notNullable();

            $table->string('payment_screenshot')->nullable(); // Store file path or URL
            $table->string('payment_reference_number')->nullable();
            $table->timestamp('payment_date')->useCurrent();

            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('invoice_id')->references('id')->on('trn_invoice')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('pm_payment_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trn_payments');
    }
};
