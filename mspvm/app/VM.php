<?php namespace App;

use App\Backup\BackupMethod;
use App\UI\Table\VMDetailsTable;
use App\Virtualization\OpenVZ;
use App\Virtualization\VMStats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

Class VM extends Model  {
    protected $table = 'vps';

    protected $fillable = [
        'hostname',
        'disk',
        'inode_limit',
        'ram',
        'burst',
        'swap',
        'cpu_units',
        'cpu_limit',
        'cpus',
        'bandwith_limit',
        'server_id',
        'package_id',
        'virt_identifier',
        'primary_ip',
        'user_id',
        'suspended',
        'online',
        'tuntap',
        'ppp',
        'fuse',
        'network_speed',
        'backup_methods',
        'resource_pool_id'
    ];

    private $stats;

    public function user() {
        return User::find($this->user_id);
    }

    public function ips() {
        return IP::where('vps_id', '=', $this->id)->get()->toArray();
    }

    /**
     * @return Server
     */
    public function server() {
        return $this->belongsTo('App\Server')->first();
    }

    public function getName() {
        return $this->hostname.' ('.$this->primary_ip.')';
    }

    public function getShortDetails() {
        return VMDetailsTable::short($this);
    }

    function formatBytes($size, $forceBase = false, $includeSuffix = true) {
        $size = $size * 1024;

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
            return number_format(pow(1024, $base - floor($base)), 2).$suffix;
        } else {
            return number_format($size / pow(1024, $base), 2).$suffix;
        }
    }

    public function getUsedDiskInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->getUsedDisk(), 3, $includeSuffix);
    }

    public function getTotalDiskInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->getDiskLimit(), 3, $includeSuffix);
    }

    public function getUsedDiskPercentage() {
        return round(($this->getUsedDisk() / $this->getDiskLimit()) * 100);
    }

    public function getUsedRAMInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->getUsedRAM(), 2, $includeSuffix);
    }

    public function getTotalRAMInFriendlyFormat($includeSuffix = true) {
        return $this->formatBytes($this->getRAMLimit(), 2, $includeSuffix);
    }

    public function getUsedRAMPercentage() {
        return round(($this->getUsedRAM() - $this->getRAMLimit()) * 100);
    }

    public function getUsedCPU() {
        return $this->stats()->getCPUUtilization();
    }

    public function getUsedCPUPercentage() {
        return $this->stats()->getCPUUtilizationAsPercentage();
    }

    public function getUsedDisk() {
        return $this->stats()->getUsedDisk();
    }

    public function getDiskLimit() {
        return $this->stats()->getTotalDisk();
    }

    public function getBandwidthLimit() {
        return $this->bandwith_limit  * pow(1024, 2);
    }

    /**
     * @TODO rename to getUsedDiskPercentage...
     *
     * @return int
     */
    public function getDiskRoundedToNearestFive() {
        $percentage = 100 - round(($this->getUsedDisk() / $this->getDiskLimit()) * 100);

        while ($percentage % 5 > 0) {
            $percentage++;
        }

        return $percentage;
    }

    public function getUsedRAM() {
        return $this->stats()->getUsedRAM();
    }

    public function getRAMLimit() {
        return $this->stats()->getTotalRAM();
    }

    public function getRAMRoundedToNearestFive() {
        $percentage = 100 - round(($this->getUsedRAM() / $this->getRAMLimit()) * 100);

        while ($percentage % 5 > 0) {
            $percentage++;
        }

        return $percentage;
    }

    public function getTotalBandwidthInFriendlyFormat() {
        return $this->formatBytes($this->getBandwidthLimit());
    }

    public function getUsedBandwidthInFriendlyFormat() {
        return $this->formatBytes($this->getUsedBandwidth());
    }

    /**
     * @TODO get from stats
     *
     * @return int
     */
    public function getUsedBandwidth() {
        return rand(1,99) * pow(1024, 2);
    }

    public function getUsedBandwidthAsPercentage() {
        return 10;
        return ($this->getUsedBandwidth() / $this->getBandwidthLimit()) / 100;
    }

    /**
     * Get cached VM stats
     *
     * @return VMStats
     */
    public function stats() {
        if (!is_null($this->stats)) {
            return $this->stats;
        }

        return $this->stats = $this->virtualization()->getVMStats(Cache::remember('vm.stats.'.$this->id, 0, function () {
            if (!pingSSH($this->server()->ip)) {
                return (object) [];
            }

            $stats = $this->virtualization()->vm_stats($this);

            if (empty($stats)) {
                return (object) [

                ];
            }

            $stats = $stats[0];

            $stats->time = time();

            return $stats;
        }));
    }

    function hasBackupMethodEnabled($method) {
        if (is_object($method) && $method instanceof BackupMethod) {
            $method = $method->getID();
        }

        return in_array($method, array_keys($this->getBackupMethods()));
    }

    function enableBackupMethod($method) {
        if (is_object($method) && $method instanceof BackupMethod) {
            $method = $method->getID();
        }

        $methods = $this->getBackupMethods();

        $methods[$method] = [];

        $this->backup_methods = json_encode($methods);

        $this->update([
           'backup_methods' => json_encode($methods)
        ]);
    }

    function disableBackupMethod($method) {
        if (is_object($method) && $method instanceof BackupMethod) {
            $method = $method->getID();
        }

        $this->backup_methods = json_encode(
            array_filter($this->getBackupMethods(), function ($m) use($method) {
                return $m != $method;
            }, ARRAY_FILTER_USE_KEY)
        );

        $this->update([
            'backup_methods' => $this->backup_methods
        ]);
    }

    public function updateBackupMethodSettings($method, array $data) {
        if (is_object($method) && $method instanceof BackupMethod) {
            $method = $method->getID();
        }

        if (!$this->hasBackupMethodEnabled($method)) {
            return;
        }

        $methods = $this->getBackupMethods();
        $methods[$method] = $data;

        $this->update([
            'backup_methods' => json_encode($methods)
        ]);
    }

    public function getBackupMethods() {
        $methods = $this->backup_methods;

        if ($methods = @json_decode($methods, true)) {
            return $methods;
        }

        return [];
    }

    /**
     * Get backup methods enabled for this server
     *
     * @return BackupMethod[]
     */
    function getEnabledBackupMethods() {
        $methods = array();
        foreach (app('backup') as $method) {
            if (isset($this->getBackupMethods()[get_class($method)])) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * Get uptime in seconds
     *
     * This method adjust for cache update and assumes that the VM has been online in the meantime.
     *
     * @return mixed
     */
    public function getUptime() {
        return $this->stats()->getUptime() + (time() - $this->stats()->time);
    }

    /**
     * Get uptime representation in seconds
     *
     * <span class="d">D <b>days</b></span> <span class="h">H <b>hours</b></span> <span class="m">M <b>minutes</b></span>
     *
     * This method adjust for cache update and assumes that the VM has been online in the meantime.
     *
     * @return string
     */
    public function getUptimeForHumans() {
        $uptime = $this->getUptime();

        $days = floor($uptime / (24*3600));

        $hours = floor(($uptime - $days * 24*3600) / 3600);

        $minutes = ceil(($uptime - $days * 24*3600 - $hours * 3600)/ 60);

        return '<span class="d">'.$days.' <b>days</b></span> <span class="h">'.$hours.' <b>hours</b></span> <span class="m">'.$minutes.' <b>minutes</b></span>';
    }

    public function getLoadAverageRepresentation() {

    }

    public function getOS() {
        return $this->stats()->getOS();
    }

    public function getMainIP() {
        return $this->primary_ip;
    }

    public function getVirtID() {
        return $this->virt_identifier;
    }

    public function getHostname() {
        return $this->stats()->getHostname();
    }

    /**
     * @return OpenVZ
     */
    public function virtualization() {
        return new OpenVZ($this->server());
    }
}