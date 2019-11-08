<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignedCaregiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_caregivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('caregiver_id');
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::table('assigned_caregivers', function (Blueprint $table) {
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
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
        Schema::table('assigned_caregivers', function(Blueprint $table){
            $table->dropForeign('assigned_caregivers_booking_id_foreign');
            $table->dropForeign('assigned_caregivers_caregiver_id_foreign');
            $table->dropColumn('booking_id');
            $table->dropColumn('caregiver_id');
         });
        Schema::dropIfExists('assigned_caregivers');
    }
}
