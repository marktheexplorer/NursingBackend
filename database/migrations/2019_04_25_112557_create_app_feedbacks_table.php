<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('rating');
            $table->string('text')->nullable();
            $table->timestamps();
        });

        Schema::table('app_feedbacks', function (Blueprint $table) {
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
        Schema::table('app_feedbacks', function(Blueprint $table){
            $table->dropForeign('app_feedbacks_user_id_foreign');
            $table->dropColumn('user_id');
         });
        Schema::dropIfExists('app_feedbacks');
    }
}
