<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_medications', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('assign_id')->unsigned();
            $table->foreign('assign_id')->references('id')->on('assign_medications');
            
            $table->integer('resident')->unsigned();
            $table->foreign('resident')->references('id')->on('users');

            $table->integer('user')->unsigned();
            $table->foreign('user')->references('id')->on('users');

            $table->string('comment', '2048')->nullable();

            $table->datetime('sign_date');

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
        Schema::dropIfExists('user_medications');
    }
}
