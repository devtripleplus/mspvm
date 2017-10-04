<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('user');
            $table->string('ip');
            $table->string('key');
            $table->string('type');
            $table->smallInteger('password');
            $table->string('url');
            $table->smallInteger('port');
            $table->string('status_type');
            $table->string('location');
            $table->smallInteger('status');
            $table->smallInteger('status_warning');
            $table->unsignedInteger('last_check');
            $table->unsignedInteger('previous_check');
            $table->unsignedInteger('up_since');
            $table->unsignedInteger('down_since');
            $table->unsignedInteger('alert_after');
            $table->string('load_alert');
            $table->string('ram_alert');
            $table->string('hard_disk_alert');
            $table->smallInteger('display_memory');
            $table->smallInteger('display_hard_disk');
            $table->smallInteger('display_network_uptime');
            $table->smallInteger('display_location');
            $table->smallInteger('display_history');
            $table->smallInteger('display_statistics');
            $table->smallInteger('display_hs');
            $table->smallInteger('display_bandwidth');
            $table->unsignedInteger('hardware_uptime');
            $table->unsignedInteger('total_memory');
            $table->unsignedInteger('free_memory');
            $table->unsignedInteger('load_average');
            $table->unsignedInteger('hard_disk_free');
            $table->unsignedInteger('hard_disk_total');
            $table->unsignedInteger('bandwidth');
            $table->unsignedInteger('last_bandwidth');
            $table->unsignedInteger('container_bandwidth');
            $table->unsignedInteger('bandwidth_timestamp');
            $table->string('volume_group');
            $table->string('qemu_path');
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
        Schema::drop('servers');
    }
}
