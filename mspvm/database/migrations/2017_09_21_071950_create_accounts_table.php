<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->string('username');
            $table->smallInteger('access_level');
            $table->string('email_address');
            $table->string('password');
            $table->smallInteger('permissions');
            $table->string('salt');
            $table->string('activation_code');
            $table->string('forgot');
            $table->increments('id');
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
        Schema::drop('accounts');
    }
}
