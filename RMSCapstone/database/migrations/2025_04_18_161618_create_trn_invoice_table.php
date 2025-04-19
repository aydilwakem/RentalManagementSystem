<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrnInvoiceTable extends Migration
{
    public function up()
    {
        Schema::create('trn_invoice', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('invoice_number')->unique(); // Unique invoice identifier
            $table->enum('invoice_type', ['House', 'Event_Hall', 'Room', 'Activity']); // Type of invoice
            $table->decimal('total_amount', 10, 2); // Total invoice amount
            $table->enum('payment_status', ['pending', 'paid', 'partially paid', 'failed'])->default('pending'); // Payment status
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('trn_invoice');
    }
}
