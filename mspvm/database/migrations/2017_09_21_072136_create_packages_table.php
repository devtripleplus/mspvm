<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('ram');
            $table->unsignedInteger('disk');
            $table->unsignedInteger('swap');
            $table->unsignedInteger('cpu_units');
            $table->unsignedInteger('cpu_limit');
            $table->unsignedInteger('bandwith_limit');
            $table->unsignedInteger('inode_limit');
            $table->unsignedInteger('network_speed');
            $table->unsignedInteger('burst');
            $table->unsignedInteger('cpus');
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
        Schema::drop('packages');
    }
}
