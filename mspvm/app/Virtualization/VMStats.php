<?php namespace App\Virtualization;

Interface VMStats {
    public function isAvailable();

    public function getTotalDisk();

    public function getUsedDisk();

    public function getFreeDisk();

    public function getTotalRAM();

    public function getUsedRAM();

    public function getFreeRAM();

    public function getCPUUtilization();

    public function getCPUUtilizationAsPercentage();

    public function getOS();

    public function getHostname();

    public function getUptime();
}