<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaregiverAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caregiver_attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caregiver_id');
            $table->string('value');
            $table->string('type')->default('non')->comment('store caregiver attributes like service zipcode, non service zipcode, qualificaiton, service etc. ');
            $table->timestamps();
        });

        Schema::table('caregiver_attributes', function (Blueprint $table) {
            $table->foreign('caregiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('caregiver_attributes', function(Blueprint $table){
            $table->dropForeign('caregiver_attributes_caregiver_id_foreign');
            $table->dropColumn('caregiver_id');
         });
        Schema::dropIfExists('caregiver_attributes');
    }
}
