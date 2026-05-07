<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedInteger('quantity');
            $table->dateTime('service_datetime')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, remove if not needed)
            $table->foreign('transaction_id')->references('id')->on('trn_transactions')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('prd_services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('transaction_services', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['service_id']);
        });

        Schema::dropIfExists('transaction_services');
    }
};
