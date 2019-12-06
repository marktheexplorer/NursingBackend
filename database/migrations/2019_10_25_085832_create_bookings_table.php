<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('relation_id')->nullable();
            $table->string('height');
            $table->string('weight');
            $table->string('pets');
            $table->string('diagnosis_id')->nullable();
            $table->unsignedBigInteger('service_location_id')->nullable();
            $table->string('address');
            $table->string('county')->nullable();
            $table->string('state');
            $table->string('country');
            $table->string('zipcode')->nullable();
            $table->string('booking_type');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('weekdays')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->boolean('24_hours')->default(0);
            $table->integer('no_of_weeks')->nullable();
            $table->unsignedBigInteger('caregiver_id')->nullable();
            $table->string('status')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('relation_id')->references('id')->on('relations')->onDelete('cascade');
            $table->foreign('caregiver_id')->references('id')->on('caregiver')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('bookings', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropForeign(['relation_id']);
            $table->dropForeign(['caregiver_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('relation_id');
            $table->dropColumn('caregiver_id');
         });
        Schema::dropIfExists('bookings');
    }
}
