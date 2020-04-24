<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('question');
            $table->longText('answer');
            $table->unsignedBigInteger('role_id');;
            $table->tinyInteger('faq_order')->default('0');
            $table->timestamps();
        });

        Schema::table('faqs', function (Blueprint $table) {
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
        Schema::table('faqs', function(Blueprint $table){
            $table->dropForeign('faqs_role_id_foreign');
            $table->dropColumn('role_id');
         });
        Schema::dropIfExists('faqs');
    }
}
