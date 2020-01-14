<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountyAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('county_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('county');
            $table->string('area');
            $table->integer('is_blocked')->default(1);
            $table->integer('is_area_blocked')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('county_areas');
    }
}
