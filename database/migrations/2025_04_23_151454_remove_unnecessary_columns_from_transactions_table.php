<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            // Foreign key drops must be done before dropping the column

            $table->dropColumn([
                'reservation_id',
                'room_id',
                'activity_id',
                'check_in_time',
                'check_out_time',
                'check_in_date',
                'check_out_date',
                'first_name',
                'middle_name',
                'last_name',
                'suffix',
                'email',
                'contact_number',
                'house_number',
                'street',
                'barangay',
                'city_municipality',
                'province',
                'region',
                'postal_code',
                'country',
                'total_females',
                'total_males',
                'total_infants',
                'total_people',
                'pets',
                'payment_method_id',
                'payment_screenshot',
                'payment_reference_number',
                'isPaid',
                'isReserved',
                'isConfirmed',
                'actual_check_in_time',
                'actual_check_out_time',
                'actual_check_in_date',
                'actual_check_out_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('trn_transactions', function (Blueprint $table) {
            $table->bigInteger('reservation_id')->nullable();
            $table->unsignedbigInteger('room_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('suffix', 10)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('house_number', 20)->nullable();
            $table->string('street', 150)->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('city_municipality', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country', 50)->nullable();
            $table->integer('total_females')->nullable();
            $table->integer('total_males')->nullable();
            $table->integer('total_infants')->nullable();
            $table->integer('total_people')->nullable();
            $table->string('pets')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('payment_screenshot', 255)->nullable();
            $table->string('payment_reference_number', 100)->nullable();
            $table->boolean('isPaid')->nullable();
            $table->boolean('isReserved')->nullable();
            $table->boolean('isConfirmed')->nullable();
            $table->time('actual_check_in_time')->nullable();
            $table->time('actual_check_out_time')->nullable();
            $table->date('actual_check_in_date')->nullable();
            $table->date('actual_check_out_date')->nullable();
        });
    }
};
