<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceRequestsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_requests_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_request_id');
            $table->string('value');
            $table->string('type')->comment('store caregiver attributes like service zipcode, non service zipcode, qualificaiton, service etc.');
            $table->timestamps();
        });

        Schema::table('service_requests_attributes', function (Blueprint $table) {
            $table->foreign('service_request_id')->references('id')->on('service_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_requests_attributes', function(Blueprint $table){
            $table->dropForeign('service_requests_attributes_service_request_id_foreign');
            $table->dropColumn('service_request_id');
         });
        
        Schema::dropIfExists('service_requests_attributes');
    }
}
