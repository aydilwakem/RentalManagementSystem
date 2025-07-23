<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_guest_pets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->json('pet_breed');
            $table->unsignedTinyInteger('pet_count')->default(1);
            $table->unsignedInteger('nights_stayed')->default(1);
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->string('vaccination_card')->nullable(); // file path
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('trn_transactions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_guest_pets');
    }
};
