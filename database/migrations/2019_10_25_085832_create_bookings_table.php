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
            $table->unsignedBigInteger('relation_id');
            $table->string('height');
            $table->string('weight');
            $table->string('pets');
            $table->unsignedBigInteger('diagnosis_id');
            $table->unsignedBigInteger('service_location_id')->nullable();
            $table->string('address');
            $table->string('county');
            $table->string('state');
            $table->string('country');
            $table->string('zipcode');
            $table->string('booking_type');
            $table->unsignedBigInteger('caregiver_assigned')->nullable();
            $table->string('start_date');
            $table->string('end_date');
            $table->string('weekdays')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->boolean('24_hours')->default(0);
            $table->integer('no_of_weeks')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('relation_id')->references('id')->on('relations')->onDelete('cascade');
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis')->onDelete('cascade');
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
            $table->dropForeign('bookings_user_id_foreign');
            $table->dropForeign('bookings_relation_id_foreign');
            $table->dropForeign('bookings_diagnosis_id_foreign');
            $table->dropForeign('bookings_service_location_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('relation_id');
            $table->dropColumn('diagnosis_id');
         });
        Schema::dropIfExists('bookings');
    }
}
