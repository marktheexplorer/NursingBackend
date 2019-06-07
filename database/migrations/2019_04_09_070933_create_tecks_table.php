<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tecks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type',['my', 'event', 'boss', 'quick', 'group', 'guest']);
            $table->string('title');
            $table->string('mark_trip')->nullable();
            $table->string('mark_trip_other')->nullable();
            $table->string('start_location');
            $table->string('end_location');
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('start_time');
            $table->string('end_time');
            $table->string('threshold_min_time')->nullable();
            $table->string('threshold_max_time')->nullable();
            $table->string('eta');
            $table->string('start_lat_lng');
            $table->string('end_lat_lng');
            $table->string('current_lat_lng')->nullable();
            $table->string('start_location_city')->nullable();
            $table->string('start_location_state')->nullable();
            $table->string('start_location_country')->nullable();
            $table->string('repetitions')->nullable();
            $table->boolean('status')->default(1);//completed on his way started
            $table->boolean('is_active')->default(1);//to make tt active inactive acc to user current location.
            $table->integer('count')->nullable();
            $table->boolean('is_notify')->default(1);
            $table->timestamps();
        });

        Schema::table('tecks', function (Blueprint $table) {
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
        Schema::dropIfExists('tecks');
    }
}
