<?php namespace App;

use Illuminate\Database\Eloquent\Model;

Class ResourcePool extends Model {
    private $vms = null;

    protected $fillable = [
      'disk',
        'swap',
        'ips',
        'ram',
        'package_id',
        'user_id',
        'status'
    ];

    public function getTotalDisk() {
        return $this->disk;
    }

    public function getUsedDisk() {
        $used = 0;
        foreach ($this->vms() as $vm) {
            $used += $vm->disk;
        }

        return $used;
    }

    public function getUsedDiskPercentage() {
        return ($this->getUsedDisk() / $this->getTotalDisk()) * 100;
    }

    public function getFreeDiskPercentage() {
        return 100 - $this->getUsedDiskPercentage();
    }

    public function getFreeDisk() {
        return $this->getTotalDisk() - $this->getUsedDisk();
    }

    public function getTotalRAM() {
        return $this->ram;
    }

    public function getUsedRAM() {
        $used = 0;
        foreach ($this->vms() as $vm) {
            $used += $vm->ram;
        }

        return $used;
    }

    public function getFreeRAM() {
        return $this->getTotalRAM() - $this->getUsedRAM();
    }

    public function getUsedRAMPercentage() {
        return ($this->getUsedRAM() / $this->getTotalRAM()) * 100;
    }

    public function getFreeRAMPercentage() {
        return 100 - $this->getUsedRAMPercentage();
    }

    public function getTotalSwap() {
        return $this->swap;
    }

    public function getUsedSwap() {
        $used = 0;
        foreach ($this->vms() as $vm) {
            $used += $vm->swap;
        }

        return $used;
    }

    public function getFreeSwap() {
        return $this->getTotalSwap() - $this->getUsedSwap();
    }

    public function getUsedSwapPercentage() {
        return ($this->getUsedSwap() / $this->getTotalSwap()) * 100;
    }

    public function getFreeSwapPercentage() {
        return 100 - $this->getUsedSwapPercentage();
    }

    public function getIPCount() {
        return $this->ips;
    }

    public function getAllocatedIPCount() {
        $used = 0;
        foreach ($this->vms() as $vm) {
            $used += count($vm->ips());
        }

        return $used;
    }

    public function getAvailableIPCount() {
        return $this->getIPCount() - $this->getAllocatedIPCount();
    }

    /**
     * @return VM[]
     */
    public function vms() {
        if (!is_null($this->vms)) {
            return $this->vms;
        }

        return $this->vms = VM::where('resource_pool_id', $this->id)->get();
    }

    public function package() {
        return Package::find($this->package_id);
    }

    /**
     * @param $disk
     * @param $ram
     * @param $swap
     * @param $ips
     * @return Package
     * @throws \Exception
     */
    public function mockPackage($disk, $ram, $swap, $ips) {
        $package = Package::find($this->package_id);

        if ($disk < $package->disk) {
            throw new \Exception('Storage must be higher than '.$package->disk.'MB');
        }

        if ($ram < $package->ram) {
            throw new \Exception('RAM must be higher than '.$package->ram.'MB');
        }

        if ($swap < $package->swap) {
            throw new \Exception('SWAP must be higher than '.$package->swap.'MB');
        }

        if ($disk > $this->getFreeDisk()) {
            throw new \Exception('This disk space is unavailable on the resource pool!');
        }

        if ($ram > $this->getFreeRAM()) {
            throw new \Exception('This RAM is unavailable on the resource pool!');
        }

        if ($swap > $this->getFreeSwap()) {
            throw new \Exception('This SWAP is unavailable on the resource pool!');
        }

        if (1==2 && $ips > $this->getAvailableIPCount()) {
            throw new \Exception('The allocated IPs exceed the available IP count!');
        }

        $ratio = floor($ram / $package->ram);

        $package->disk = $disk;
        $package->ram = $ram;
        $package->swap = $swap;

        // scale CPU
        $package->cpus = $package->cpus * $ratio;
        $package->cpu_units = $package->cpu_units * $ratio;

        return $package;
    }
}