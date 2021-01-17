<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBodyHarmTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_harms', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('resident')->unsigned();
            $table->foreign('resident')->references('id')->on('users');

            $table->datetime('sign_date');

            $table->integer('comment')->unsigned();
            $table->foreign('comment')->references('id')->on('body_harm_comments');

            $table->string('screenshot_3d', '2048');
            
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
        Schema::dropIfExists('body_harms');
    }
}
