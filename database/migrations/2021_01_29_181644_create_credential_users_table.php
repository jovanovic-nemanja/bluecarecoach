<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credential_users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('userid')->unsigned();
            $table->foreign('userid')->references('id')->on('users');
            
            $table->integer('credentialid');
            $table->text('file_name');

            $table->datetime('expire_date');
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
        Schema::dropIfExists('credential_users');
    }
}
