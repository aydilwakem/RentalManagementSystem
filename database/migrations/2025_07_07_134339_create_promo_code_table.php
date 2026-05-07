<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {

            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();

            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 10, 2);

            $table->integer('max_uses')->nullable();
            $table->integer('uses_count')->default(0);
            $table->integer('per_user_limit')->default(1);
            $table->decimal('min_booking_amount', 10, 2)->nullable();

            // Expiration logic
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('duration_days')->nullable();
            $table->boolean('has_expiration')->default(false);

            $table->boolean('is_active')->default(true);

            // Optional associations
            $table->unsignedBigInteger('property_category_id')->nullable();
            $table->foreign('property_category_id')->references('id')->on('property_categories')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
};
