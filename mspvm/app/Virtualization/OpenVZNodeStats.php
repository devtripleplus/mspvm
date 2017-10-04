<?php namespace App\Virtualization;

Class OpenVZNodeStats implements NodeStats {
    public function __construct($data) {
        $this->data = $data;

        if (isset($this->data->time)) {
            $this->time = $data->time;
        } else {
            $this->time = time();
        }
    }

    public function isAvailable() {
        return isset($this->data->total_memory);
    }

    public function getTotalDisk() {
        return $this->data->disk_total;
    }

    public function getFreeDisk() {
        return $this->data->disk_free;
    }

    public function getUsedDisk() {
        return $this->getTotalDisk() - $this->getFreeDisk();
    }

    public function getTotalRAM() {
        return $this->data->total_memory;
    }

    public function getFreeRAM() {
        return $this->data->free_memory;
    }

    public function getUsedRAM() {
        return $this->getTotalRAM() - $this->getFreeRAM();
    }

    public function getUptime() {
        return $this->data->uptime;
    }

    public function getCPUUtilization() {
        return str_replace([
            '%',
            "\n"
        ], '', $this->data->cpu_util);
    }

    public function getLoadAverage() {
        return $this->data->load_average;
    }
}