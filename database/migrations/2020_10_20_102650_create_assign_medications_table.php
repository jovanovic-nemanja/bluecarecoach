<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignMedicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_medications', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('medications')->unsigned();
            $table->foreign('medications')->references('id')->on('medications');
            
            $table->integer('dose');
            // $table->integer('duration');
            
            $table->integer('resident')->unsigned();
            $table->foreign('resident')->references('id')->on('users');

            $table->string('route', '2048')->nullable();
            $table->datetime('sign_date');
            $table->time('time')->nullable();

            $table->date('start_day');
            $table->date('end_day');
            
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
        Schema::dropIfExists('assign_medications');
    }
}
