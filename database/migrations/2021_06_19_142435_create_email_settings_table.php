<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('type')->defaultValue(1);
            $table->text('from_address');
            $table->text('from_title');
            $table->text('subject');
            $table->text('content_name')->nullable();
            $table->text('content_body')->nullable();
            $table->text('pre_footer')->nullable();
            $table->text('footer')->nullable();
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
        Schema::dropIfExists('email_settings');
    }
}
