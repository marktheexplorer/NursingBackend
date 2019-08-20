<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('location');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            $table->unsignedInteger('service');
            $table->unsignedInteger('min_expected_bill');
            $table->unsignedInteger('max_expected_bill');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->longText('description');
            $table->unsignedInteger('schedule_request_id')->comment(' if 0 it would be rescheduled ');
            $table->smallInteger('status')->comment(' 0 for init, 1 for reject, 2 for approved, 3 for multiple caregiver picked, 4 for assign to caregiver, 5 for rescheduled ');
            $table->string('token')->comment('used to redirect from mail to website for upload documents');
            $table->timestamps();
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_requests', function(Blueprint $table){
            $table->dropForeign('service_requests_user_id_foreign');
            $table->dropColumn('user_id');
         });

        Schema::dropIfExists('service_requests');
    }
}
