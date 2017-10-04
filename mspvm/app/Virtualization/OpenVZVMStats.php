<?php namespace App\Virtualization;

Class OpenVZVMStats implements VMStats {
    public function __construct($data) {
        $this->data = $data;

        if (isset($data->time)) {
            $this->time = $data->time;
        } else {
            $this->time = time();
        }
    }

    public function isAvailable() {
        return count($this->data) != 1;
    }

    public function getTotalRAM() {
        return $this->data->kmemsize->limit;
    }

    public function getUsedRAM() {
        return $this->data->kmemsize->held;
    }

    public function getFreeRAM() {
        return $this->getTotalRAM() - $this->getUsedRAM();
    }

    public function getTotalDisk() {
        return $this->data->diskspace->hardlimit;
    }

    public function getUsedDisk() {
        return $this->data->diskspace->usage;
    }

    public function getFreeDisk() {
        return $this->getTotalDisk() - $this->getFreeDisk();
    }

    /**
     * @TODO
     *
     * @return int
     */
    public function getCPUUtilization() {
        return 0;
    }

    /**
     * @TODO
     *
     * @return int
     */
    public function getCPUUtilizationAsPercentage() {
        return 0;
    }

    public function getOS() {
        return $this->data->ostemplate;
    }

    public function getHostname() {
        return $this->data->hostname;
    }

    public function getUptime() {
        return $this->data->uptime;
    }
}