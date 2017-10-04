<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('server_id');
            $table->unsignedInteger('package_id');
            $table->unsignedInteger('container_id');
            $table->string('hostname');
            $table->string('primary_ip');
            $table->string('type');
            $table->unsignedInteger('ram');
            $table->unsignedInteger('burst');
            $table->unsignedInteger('swap');
            $table->unsignedInteger('disk');
            $table->unsignedInteger('cpus');
            $table->unsignedInteger('cpu_units');
            $table->unsignedInteger('cpu_limit');
            $table->unsignedInteger('inode_limit');
            $table->unsignedInteger('bandwidth_limit');
            $table->unsignedInteger('nameserver');
            $table->unsignedInteger('numiptent');
            $table->unsignedInteger('numproc');
            $table->unsignedInteger('inodes');
            $table->unsignedInteger('template_id');
            $table->boolean('online');
            $table->boolean('suspended');
            $table->unsignedInteger('suspended_admin');
            $table->unsignedInteger('bandwith_limit'); // @TODO ?
            $table->unsignedInteger('bandwidth_usage');
            $table->unsignedInteger('last_bandwidth');
            $table->unsignedInteger('rebuilding');
            $table->string('mac');
            $table->unsignedInteger('vnc_port');
            $table->string('boot_order');
            $table->string('disk_driver');
            $table->string('network_driver');
            $table->string('secondary_drive');
            $table->unsignedInteger('private_network');
            $table->unsignedInteger('ipv6');
            $table->unsignedInteger('tuntap');
            $table->unsignedInteger('ppp');
            $table->unsignedInteger('iptables');
            $table->unsignedInteger('smtp_whitelist');
            $table->unsignedInteger('iso_syncing');
            $table->string('virt_identifier');
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
        Schema::drop('vps');
    }
}
