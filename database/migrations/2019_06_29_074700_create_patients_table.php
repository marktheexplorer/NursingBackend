<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('email_verified')->default(0);
            $table->string('email_activation_token')->nullable();
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_notify')->default(1);
            $table->date('date_of_birth')->nullable();
            $table->string('mobile_number')->unique();
            $table->string('location');
            $table->string('country');
            $table->string('zip_code');
            $table->string('disease')->nullable();
            $table->string('range')->nullable();
            $table->string('availability')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
