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
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            // $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->integer('gender')->nullable();
            $table->integer('over_18')->nullable();
            $table->integer('care_giving_license')->nullable();
            $table->string('care_giving_experience')->nullable();

            $table->integer('looking_job')->nullable();
            $table->integer('looking_job_zipcode')->nullable();
            $table->integer('preferred_shift')->nullable();
            $table->integer('desired_pay_from')->nullable();
            $table->integer('desired_pay_to')->nullable();

            $table->text('street1')->nullable();
            $table->text('street2')->nullable();
            $table->text('city')->nullable();
            $table->text('zip_code')->nullable();
            $table->text('state')->nullable();

            $table->text('skill1')->nullable();
            $table->text('skill2')->nullable();
            $table->text('skill3')->nullable();
            $table->text('skill4')->nullable();
            $table->text('skill5')->nullable();

            $table->text('hobby1')->nullable();
            $table->text('hobby2')->nullable();
            $table->text('hobby3')->nullable();
            $table->text('hobby4')->nullable();
            $table->text('hobby5')->nullable();

            $table->string('profile_logo', '256')->nullable();
            $table->integer('email_verified_at')->nullable();
            $table->string('password')->nullable();
            
            $table->string('fb_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('apple_id')->nullable();

            $table->string('phone_number')->nullable();
            $table->datetime('sign_date');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
