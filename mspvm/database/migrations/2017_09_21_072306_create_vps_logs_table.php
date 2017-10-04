<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vps_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timestamp');
            $table->unsignedInteger('vps_id');
            $table->text('command');
            $table->text('entry');
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
        Schema::drop('vps_logs');
    }
}
