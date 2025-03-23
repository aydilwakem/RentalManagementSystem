<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trn_transactions', function (Blueprint $table) {

            // Reservation flow 

            // Form for choosing rooms and activity
            $table->id('id');
            $table->foreignId('room_id')->constrained('prd_rooms'); // Fetches the active room rate associated to this room. To be accumulated in total_amount.
            $table->time('check_in_time');
            $table->time('check_out_time');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer(column: 'total_adults'); // System scans if the chosen number of adults is equal or less than the max_adults. If not, the additional rate will be accumulated.
            $table->integer('total_kids'); // Same with total adults
            $table->integer('pax'); // Sums up the total adults and total kids -> needs to match the ideal guest of chosen room
            $table->foreignId('activity_id')->nullable()->constrained('prd_activities'); // Guest is allowed not to chooose an activity

            // System computes the rooms and activity amount
            $table->decimal('total_amount', 10, 2);

            // Guest fillable again
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 10)->nullable();
            $table->string('email', 100);
            $table->string('contact_number', 20);
            $table->string('house_number', 20);
            $table->string('street', 150);
            $table->string('barangay', 100);
            $table->string('city_municipality', 100);
            $table->string('province', 100);
            $table->string('region', 50);
            $table->string('postal_code', 10);
            $table->string('country', 50);
            $table->integer('total_females');
            $table->integer('total_males');
            $table->integer('total_infants');
            $table->integer('total_people');
            $table->integer('pets')->default(0);
            $table->boolean('terms');
            $table->foreignId('payment_method_id')->constrained('pm_payment_methods');
            $table->string('payment_screenshot', 255)->nullable();
            $table->string('payment_reference_number', 100)->nullable();

            // Admin side (can be modified)
            $table->boolean('isPaid')->default(false);
            $table->boolean('isReserved')->default(false);
            $table->boolean('isConfirmed')->default(false);
            $table->time('actual_check_in_time')->nullable();
            $table->time('actual_check_out_time')->nullable();
            $table->date('actual_check_in_date')->nullable();
            $table->date('actual_check_out_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
