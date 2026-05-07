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
        Schema::create('trn_guest_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('guest_type_id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('suffix');
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->enum('residency', ['local', 'foreigner'])->default('local');
            $table->string('country_of_origin');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('transaction_id')->references('id')->on('trn_transactions');
            $table->foreign('guest_type_id')->references('id')->on('trn_guest_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trn_guest_details');
    }
};
