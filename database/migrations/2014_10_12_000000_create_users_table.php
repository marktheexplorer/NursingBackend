<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id');
            $table->string('f_name');
            $table->string('m_name')->nullable();
            $table->string('l_name');
            $table->string('email')->unique();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('language')->nullable();
            $table->boolean('email_verified')->default(0);
            $table->string('email_activation_token')->nullable();
            $table->string('country_code')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('mobile_number')->nullable()->unique();
            $table->boolean('mobile_number_verified')->default(0);
            $table->string('otp')->nullable();  
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_notify')->default(1);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('street')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('password')->nullable();
            $table->string('carepack_mail_token')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('document')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
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
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('users_role_id_foreign');
            $table->dropColumn('role_id');
         });
        Schema::dropIfExists('users');
    }
}
