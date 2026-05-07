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
        Schema::table('property_features', function (Blueprint $table) {
            $table->integer('quantity')->default(1);
            $table->enum('property_feature_type', ['appliance', 'equipment', 'utility', 'entertainment', 'service', 'fixture'])->after('quantity');
            $table->boolean('is_active')->default(true)->after('property_feature_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_features', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'property_feature_type', 'is_active']);
        });
    }
};
