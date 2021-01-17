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
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->integer('gender')->nullable();
            $table->date('birthday')->nullable();

            $table->text('street1')->nullable();
            $table->text('street2')->nullable();
            $table->text('city')->nullable();
            $table->text('zip_code')->nullable();
            $table->text('state')->nullable();

            $table->string('profile_logo', '256');
            $table->integer('email_verified_at')->nullable();
            $table->string('password')->nullable();
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
