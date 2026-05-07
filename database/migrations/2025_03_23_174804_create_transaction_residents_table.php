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
        Schema::create('trn_residents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id'); // Foreign Key reference
            $table->string('name');
            $table->enum('residency_status', ['Local', 'Foreigner']);
            $table->string('residency')->nullable(); // City or Province for locals
            $table->string('country')->nullable(); // Only for foreigners
            $table->enum('demographic', ['female', 'male', 'infant']); // Added demographic ENUM
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('transaction_id')->references('id')->on('trn_transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trn_residents');
    }
};
