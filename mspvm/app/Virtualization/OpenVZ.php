<?php namespace App\Virtualization;

use App\IP;
use App\Server;
use App\Template;
use App\VM;
use Illuminate\Support\Str;

Class OpenVZ {
    public function __construct(Server $server) {
        $this->server = $server;
        $this->openvz = new \OpenVZ($server->ip);
    }

    public function getName() {
        return 'OpenVZ';
    }

    /**
     * @TODO
     *
     * @return string
     */
    public function getIcon() {
        return 'https://forum.openvz.org/theme/ovz3/images/header.gif';
    }

    public function undeploy_template(Template $template) {
        $filename = Str::slug($template->name).'_'.get_friendly_arc_name($template->architecture).'.'.$this->getExtension($template->file);

        $this->exec('rm -f /vz/template/cache/'.$filename);
    }

    /**
     * Requires the password to be passed in the params array
     *
     * @param array $params
     */
    public function setup(array $params) {
        $this->openvz->addServer(
            $this->server->ip,
            $params['password']
        );
    }

    public function info() {
        return json_decode($this->openvz->nodeInfo());
    }

    public function vm_stats(VM $vm) {
        return $this->openvz->get_stats($vm->virt_identifier);
    }

    public function reinstall(VM $vm) {
        $this->openvz->rebuild($vm->virt_identifier);
    }

    public function delete(VM $vm) {
        $this->openvz->destroy($vm->virt_identifier);
    }

    public function setHostname(VM $vm, $hostname) {
        $this->openvz->set_hostname($vm->virt_identifier, $hostname);
    }

    public function suspend(VM $vm) {
        $this->openvz->suspend($vm->virt_identifier);
    }

    public function setPassword(VM $vm, $password) {
        $this->openvz->set_password($vm->virt_identifier, $password);
    }

    public function unsuspend(VM $vm) {
        $this->openvz->unsuspend($vm->virt_identifier);
    }

    public function enableTuntap(VM $vm) {
        $this->openvz->enable_tuntap($vm->virt_identifier);
    }

    public function disableTuntap(VM $vm) {
        $this->openvz->disable_tuntap($vm->virt_identifier);
    }

    public function enablePPP(VM $vm) {
        $this->openvz->enable_ppp($vm->virt_identifier);
    }

    public function disablePPP(VM $vm) {
        $this->openvz->disable_ppp($vm->virt_identifier);
    }

    public function enableFuse(VM $vm) {
        $this->openvz->enable_fuse($vm->virt_identifier);
    }

    public function rebuild($vm, Template $template, $password, $dns1, $dns2) {
        $this->openvz->rebuild((object) [
            'ctid' => $vm->virt_identifier,
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
            'cpus' => $vm->cpus
        ]);

        $this->openvz->addip((object) [
            'ctid' => $vm->virt_identifier,
            'ip' => $vm->primary_ip
        ]);

        // Set TC control on all IPs
        $this->setTCControl($vm, $vm->network_speed, $vm->network_speed);
    }

    public function removeIP(VM $vm, IP $ip) {
        $this->openvz->delip((object) [
           'ip' => $ip->ip_address,
            'ctid' => $vm->virt_identifier
        ]);
    }

    public function addIP(VM $vm, IP $ip) {
        $this->openvz->addip((object) [
            'ip' => $ip->ip_address,
            'ctid' => $vm->virt_identifier
        ]);
    }

    public function reboot(VM $vm) {
        $this->openvz->vrestart($vm->virt_identifier);
    }

    public function start(VM $vm) {
        $this->openvz->start($vm->virt_identifier);
    }

    public function stop(VM $vm) {
        $this->openvz->stop($vm->virt_identifier);
    }

    public function getPublicKey() {
        return '/var/mspvm/keys/' . sha1($this->server->ip . \servers::$salt).'.pub';
    }

    public function getPrivateKey() {
        return '/var/mspvm/keys/' . sha1($this->server->ip . \servers::$salt).'.txt';
    }

    /**
     * @param $data
     * @return OpenVZNodeStats
     */
    public function getNodeStats($data) {
        return new OpenVZNodeStats($data);
    }


    /**
     * @param $data
     * @return OpenVZVMStats
     */
    public function getVMStats($data) {
        return new OpenVZVMStats($data);
    }

    public function setTCControl(VM $vm, $in, $out, array $ips = []) {
        if (empty($ips)) {
            $ips = (object) array_map(function ($ip) {
                return $ip['ip_address'];
            }, $vm->ips());
        } else {
            $ips = (object) $ips;
        }

        // reset eth0
        $this->openvz->tc_destroy((object) [
            'ctid' => $vm->virt_identifier,
            'interface' => 'eth0'
        ]);

        // reset eth1
        $this->openvz->tc_destroy((object) [
            'ctid' => $vm->virt_identifier,
            'interface' => 'eth1'
        ]);

        // eth0
        $this->openvz->tc_create((object) [
            'ctid' => $vm->virt_identifier,
            'interface' => 'eth0',
            'bwin' => $in,
            'bwout' => $out,
            'ips' => $ips
        ]);

        // eth0
        $this->openvz->tc_create((object) [
            'ctid' => $vm->virt_identifier,
            'interface' => 'eth1',
            'bwin' => $in,
            'bwout' => $out,
            'ips' => $ips
        ]);
    }

    public function build(VM $vm, $params, IP $ip) {
        $this->openvz->create((object) $params);

        $this->openvz->addip((object) [
            'ctid' => $vm->id + 100,
            'ip' => $ip->ip_address
        ]);

        $this->setTCControl($vm, $params['network_speed'], $params['network_speed'], [
            $ip->ip_address
        ]);

        return $vm->id + 100;
    }

    private function exec($command) {
        $this->openvz->exec($command);
    }

    public function createBackup(VM $vm, $file, $execute = false) {
        $r = $this->exec('vzdump --compress '.$vm->getVirtID());
        // Creating archive '
        $filename = preg_match('#Creating archive\\\'#si', $r, $matches);
        if ($execute) {
            return $this->exec(str_replace('FILE', $filename, $execute));
        }
    }

    public function restoreBackup(VM $vm, $file) {
        $this->exec('
        download
        vzdump --restore '.basename($file).' '.$vm->getVirtID()
       .'unlink file');
    }

    public function deploy_template(Template $template, &$error) {
        return $error = $this->openvz->deploy_template(
                $template
            ) != '';
    }
}