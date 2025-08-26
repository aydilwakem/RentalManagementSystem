<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_invoice_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('discount_type_id');
            $table->decimal('discount_value', 10, 2); // computed value applied
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('invoice_id')->references('id')->on('trn_invoice')->onDelete('cascade');
            $table->foreign('discount_type_id')->references('id')->on('discount_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_invoice_discounts');
    }
};
