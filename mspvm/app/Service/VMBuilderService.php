<?php namespace App\Service;

use App\IP;
use App\Server;
use App\Template;
use App\VM;

Class VMBuilderService {
    public static function vm(VM $vm, Server $server, Template $template, $password, IP $ip, $dns1, $dns2) {
        $virtualization = $vm->virtualization();

        $params = [
            'ctid' => 100 + $vm->id,
            'template' => $template->getName(),
            'hostname' => $vm->hostname,
            'disk' => $vm->disk,
            'inodes' => $vm->inode_limit,
            'ram' => $vm->ram,
            'burst' => $vm->burst,
            'swap' => $vm->swap,
            'dns1' => $dns1,
            'dns2' => $dns2,
            'password' => $password,
            'cpu_units' => $vm->cpu_units,
            'cpu_limit' => $vm->cpu_limit,
            'network_speed' => $vm->network_speed,
            'cpus' => $vm->cpus
        ];

        $ip->update([
            'vps_id' => $vm->id
        ]);

        return $virtualization->build($vm, $params, $ip);
    }
}