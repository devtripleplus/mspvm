<?php namespace App\Virtualization;

Interface NodeStats {
    public function isAvailable();

    public function getUsedDisk();

    public function getFreeDisk();

    public function getTotalDisk();

    public function getUsedRAM();

    public function getFreeRAM();

    public function getTotalRAM();

    public function getCPUUTilization();

    /**
     * Get uptime in seconds.
     *
     * @return int
     */
    public function getUptime();
}