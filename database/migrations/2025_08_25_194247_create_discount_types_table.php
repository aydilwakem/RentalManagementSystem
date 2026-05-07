<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50); // PWD, Senior, Promo
            $table->decimal('rate', 5, 2); // percentage or fixed amount
            $table->enum('type', ['percent', 'fixed']);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_types');
    }
};
