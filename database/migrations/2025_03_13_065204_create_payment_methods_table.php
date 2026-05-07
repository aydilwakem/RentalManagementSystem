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
        Schema::create('pm_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('mode_of_payment_name', 255);
            $table->string('account_name', 100);
            $table->string('account_number', 100);
            $table->string('mode_of_payment_qr_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
