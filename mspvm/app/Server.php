<?php namespace App;

use App\Virtualization\NodeStats;
use App\Virtualization\OpenVZ;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

Class Server extends Model  {
    protected $table = 'servers';

    protected $fillable = array(
        'name',
        'ip',
        'user',
        'key'
    );

    public static $virtualization = [
        1 => 'OpenVZ'
    ];

    private $stats = null;

    /**
     * @return OpenVZ
     */
    public function virtualization() {
        return new OpenVZ($this);
    }

    function formatBytes($size, $forceBase = false, $includeSuffix = true) {
        if (!$forceBase) {
            $base = log($size) / log(1024);
        } else {
            $base = $forceBase;
        }

        if ($includeSuffix) {
            $suffix = ' '.array("", "kb", "MB", "GB", "TB")[floor($base)];
        } else {
            $suffix = '';
        }

        if (!$forceBase) {
            return round(pow(1024, $base - floor($base)), 2).$suffix;
        } else {
            return round($size / pow(1024, $base), 2).$suffix;
        }
    }

    public function getUsedDiskInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->stats()->getTotalDisk() - $this->stats()->getFreeDisk(), 3, $includeSuffix);
    }

    public function getTotalDiskInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->stats()->getTotalDisk(), 3, $includeSuffix);
    }

    public function getUsedDiskPercentage() {
        $stats = $this->stats();

        return 100 - round(($stats->getFreeDisk() / $stats->getTotalDisk()) * 100);
    }

    public function getUsedRAMInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->stats()->getTotalRAM() - $this->stats()->getFreeRAM(), 2, $includeSuffix);
    }

    public function getTotalRAMInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->stats()->getTotalRAM(), 2, $includeSuffix);
    }

    public function getUsedRAMPercentage() {
        $stats = $this->stats();

        return 100 - round(($stats->getFreeRAM() / $stats->getTotalRAM()) * 100);
    }

    public function getUsedCPU() {
        return $this->stats()->getCPUUTilization();
    }

    public function getUsedCPUPercentage() {
        return $this->stats()->getCPUUTilization();
    }

    /**
     * @TODO rename to getUsedDiskPercentage...
     *
     * @return int
     */
    public function getDiskRoundedToNearestFive() {
        $stats = $this->stats();

        $percentage = 100 - round(($stats->getFreeDisk() / $stats->getTotalDisk()) * 100);

        while ($percentage % 5 > 0) {
            $percentage++;
        }

        return $percentage;
    }

    public function getRAMRoundedToNearestFive() {
        $stats = $this->stats();

        $percentage = 100 - round(($stats->getFreeRAM() / $stats->getTotalRAM()) * 100);

        while ($percentage % 5 > 0) {
            $percentage++;
        }

        return $percentage;
    }

    public function VMCount() {
        return VM::where('server_id', '=', $this->id)->count();
    }

    public function onlineVMCount() {
        return VM::where('server_id', '=', $this->id)->count();
    }

    /**
     * @return NodeStats
     */
    public function stats() {
        if (!is_null($this->stats)) {
            return $this->stats;
        }

        if (!pingSSH($this->ip)) {
            return (object) [];
        }

        $stats = $this->virtualization()->info();

        if (!is_object($stats)) {
            $stats = (object) [];
        }

        $stats->time = time();

        return $this->stats = $this->virtualization()->getNodeStats($stats);

        // todo: re-enable caching
        return $this->stats = $this->virtualization()->getNodeStats(Cache::remember('server.stats.'.$this->id, 5, function () {
            if (!pingSSH($this->ip)) {
                return (object) [];
            }

            $stats = $this->virtualization()->info();

            if (!is_object($stats)) {
                $stats = (object) [];
            }

            $stats->time = time();

            return $stats;
        }));
    }

    public function getUptimeForHumans() {
        $uptime = $this->stats()->getUptime() + (time() - $this->stats()->getUptime());

        $days = floor($uptime / (24*3600));

        $hours = floor(($uptime - $days * 24*3600) / 3600);

        $minutes = ceil(($uptime - $days * 24*3600 - $hours * 3600)/ 60);

        return '<span class="d">'.$days.' <b>days</b></span> <span class="h">'.$hours.' <b>hours</b></span> <span class="m">'.$minutes.' <b>minutes</b></span>';
    }

    public function getKeyfile() {
        return storage_path('keys/'.$this->key);
    }
}