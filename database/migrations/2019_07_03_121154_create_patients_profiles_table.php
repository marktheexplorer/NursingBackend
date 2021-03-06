<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('diagnose_id')->nullable();
            $table->string('availability')->nullable();
            $table->string('disciplines')->nullable();
            $table->boolean('long_term')->default(0);
            $table->boolean('pets')->default(0);
            $table->text('pets_description')->nullable();
            $table->text('alt_contact_name')->nullable();
            $table->text('alt_contact_no')->nullable();
            $table->timestamps();
        });

        Schema::table('patients_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('diagnose_id')->references('id')->on('diagnosis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients_profiles', function(Blueprint $table){
            $table->dropForeign('patients_profiles_user_id_foreign');
            $table->dropForeign('patients_profiles_diagnose_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('diagnose_id');
         });
        Schema::dropIfExists('patients_profiles');
    }
}
