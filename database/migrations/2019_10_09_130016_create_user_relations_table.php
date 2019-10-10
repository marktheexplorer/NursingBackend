<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('mobile_number');
            $table->unsignedBigInteger('relation_id');
            $table->timestamps();
        });

        Schema::table('user_relations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('relation_id')->references('id')->on('relations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('user_relations', function(Blueprint $table){
            $table->dropForeign('user_relations_user_id_foreign');
            $table->dropForeign('user_relations_relation_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('relation_id');
         });
        Schema::dropIfExists('user_relations');
    }
}
