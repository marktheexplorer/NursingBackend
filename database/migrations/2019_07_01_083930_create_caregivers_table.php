<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaregiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
       Schema::create('caregiver', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('service');
            $table->float('min_price');
            $table->float('max_price');
            $table->longText('description');
            $table->string('gender');   
            $table->date('dob');
            $table->string('zipcode');
            $table->timestamps();
        });

        Schema::table('caregiver', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
         Schema::table('caregiver', function(Blueprint $table){
            $table->dropForeign('caregiver_user_id_foreign');
            $table->dropColumn('user_id');
         });
            
          Schema::dropIfExists('caregiver');
    }
}
