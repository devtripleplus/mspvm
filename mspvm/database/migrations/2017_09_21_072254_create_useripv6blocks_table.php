<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseripv6blocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('useripv6blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vps_id');
            $table->unsignedInteger('block_id');
            $table->string('user_block');
            $table->string('user_block_size');
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
        Schema::drop('useripv6blocks');
    }
}
