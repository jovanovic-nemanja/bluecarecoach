<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('activities')->unsigned();
            $table->foreign('activities')->references('id')->on('activities');
            
            $table->integer('type');    //daily, weekly and monthly(1, 2, 3
            $table->time('time')->nullable();
            $table->integer('day')->nullable(); 
            //type = 1 =====> day is null
            //type = 2 =====> day is 1 and 3....(Monday: 1, Sunday: 7)
            // type = 3 =====>day is 1 and 16th.....

            $table->integer('resident')->unsigned();
            $table->foreign('resident')->references('id')->on('users');
            
            $table->string('comment')->nullable();
            $table->string('other_comment')->nullable();
            $table->string('file', '2048')->nullable();

            $table->integer('status')->nullable();
            $table->datetime('sign_date');

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
        Schema::dropIfExists('user_activities');
    }
}
