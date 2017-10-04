<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_commands', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id');
            $table->longText('command');
            $table->unsignedInteger('interval');
            $table->unsignedInteger('last');
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
        Schema::drop('server_commands');
    }
}
