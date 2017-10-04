<?php

class OpenVZ {

    /**
     * @var
     */
    protected static $ssh;

    /**
     * @param $connection
     * @throws Exception
     */
    public function __construct($connection, $key) {
        $sKey = file_get_contents('./keys' . $key);
        $sSSH = new Net_SSH2($connection);
        try {
            if (!$sSSH->login('root', $sKey)) {

                echo 'Unable to connect to the host node, please contact customer service.';
                die();
            } else {
                $sSSH->setTimeout(30);
                self::$ssh = $sSSH;
            }
        } catch (Exception $e) {

            echo 'Unable to connect to the host node, please contact customer service.';
            die();
        }
    }

    /**
     * @param object $params
     * @return mixed
     */
    public static function create($params) {
        /* Rebuilding a server would construe using functions delete and create with the same ctid
          For template, please specify the full path and make sure it exists on the slave server
         */

        if (empty($params->hostname)) {
            $params->hostname = 'server.example.com';
        }

        if (empty($params->dns1)) {
            $params->hostname = '8.8.8.8';
        }
        if (empty($params->dns2)) {
            $params->hostname = '8.8.4.4';
        }

        $commands = " /usr/sbin/vzctl create {$params->ctid} --ostemplate {$params->template} --config basic --hostname {$params->hostname}
             /usr/sbin/vzctl set {$params->ctid} --diskspace {$params->disk}g:{$params->disk}g --save
             /usr/sbin/vzctl set {$params->ctid} --diskinodes {$params->inodes}:{$params->inodes} --save
             /usr/sbin/vzctl set {$params->ctid}  --vmguarpages {$params->ram}M --oomguarpages {$params->ram}M --privvmpages {$params->ram}M:{$params->burst}M --swap {$params->swap}M --save
             /usr/sbin/vzctl set {$params->ctid} --nameserver {$params->dns1}  --nameserver {$params->dns2} --save
             /usr/sbin/vzctl set {$params->ctid} --userpasswd root:{$params->password} --save
             /usr/sbin/vzctl set {$params->ctid} --onboot yes --save
             /usr/sbin/vzctl set {$params->ctid} --cpuunits {$params->cpu_units} --save
             /usr/sbin/vzctl set {$params->ctid} --cpulimit {$params->cpu_limit} --cpus {$params->cpus} --save
            modprobe iptables_module ipt_helper ipt_REDIRECT ipt_TCPMSS ipt_LOG ipt_TOS iptable_nat ipt_MASQUERADE ipt_multiport xt_multiport ipt_state xt_state ipt_limit xt_limit ipt_recent xt_connlimit ipt_owner xt_owner iptable_nat ipt_DNAT iptable_nat ipt_REDIRECT ipt_length ipt_tcpmss iptable_mangle ipt_tos iptable_filter ipt_helper ipt_tos ipt_ttl ipt_SAME ipt_REJECT ipt_helper ipt_owner ip_tables
             /usr/sbin/vzctl set {$params->ctid} --iptables ipt_REJECT --iptables ipt_tos --iptables ipt_TOS --iptables ipt_LOG --iptables ip_conntrack --iptables ipt_limit --iptables ipt_multiport --iptables iptable_filter --iptables iptable_mangle --iptables ipt_TCPMSS --iptables ipt_tcpmss --iptables ipt_ttl --iptables ipt_length --iptables ipt_state --iptables iptable_nat --iptables ip_nat_ftp --save
             /usr/sbin/vzctl start {$params->ctid}";
        return self::$ssh->exec($commands);
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function destroy($ctid) {
        $commands = " /usr/sbin/vzctl stop {$ctid}
             /usr/sbin/vzctl destroy {$ctid}";
        return self::$ssh->exec($commands);
    }

    /**
     * @param object $params
     */
    public static function rebuild($params) {
        if (self::vdestroy($params->ctid)) {
            self::vcreate($params);
        }
    }

    /**
     * @param object $params
     * @return string
     */
    public static function resize_disk($params) {
        $vdisk = self::$ssh->exec("/usr/sbin/vzlist {$params->ctid} -Ho diskspace");
        if (($params->disk * 1024 * 1024) > $vdisk) {
            $commands = "/usr/sbin/vzctl set {$params->ctid} --diskspace {$params->disk}G:{$params->disk}G --save;
                 ";
            return self::$ssh->exec($commands);
        }
        return "New disk size cannot be less than current disk size!";
    }

    /**
     * @param object $params
     * @return string
     */
    public static function resize_ram($params) {
        $commands = " /usr/sbin/vzctl set {$params->ctid} --ram {$params->ram}M --swap {$params->swap}M --save;";
        return self::$ssh->exec($commands);
    }

    public static function resize_cpu($params) {
        $commands = " /usr/sbin/vzctl set {$params->ctid} --cpuunits {$params->cpuu} --save;
                 /usr/sbin/vzctl set {$params->ctid} --cpulimit {$params->cpul} --cpus {$params->cpus} --save;";
        return self::$ssh->exec($commands);
    }

    public static function resize_inodes($params) {
        $commands = " /usr/sbin/vzctl set {$params->ctid} --diskinodes {$params->inodes}:{$params->inodes} --save;";
        return self::$ssh->exec($commands);
    }

    /**
     * @param object $params
     * @return mixed
     */
    public static function addip($params) {
        //If you're using ipv6 please remember to do sysctl net.ipv6.bindv6only 1 on the slave machine or ipv6 may not work with venet
        if (isset($params->ips) && count($params->ips) > 1) {
            $commands = "";
            foreach ($params->ips as $ip) {
                $commands .= " /usr/sbin/vzctl set {$params->ctid} --ipadd {$ip} --save";
            }
        } else {
            $commands = " /usr/sbin/vzctl set {$params->ctid} --ipadd {$params->ip} --save";
        }
        return self::$ssh->exec($commands);
    }

    /**
     * @param object $params
     * @return mixed
     */
    public static function delip($params) {
        if (isset($params->ips) && count($params->ips) > 1) {
            $commands = "";
            foreach ($params->ips as $ip) {
                $commands .= " /usr/sbin/vzctl set {$params->ctid} --ipdel {$ip} --save";
            }
        } else {
            $commands = " /usr/sbin/vzctl set {$params->ctid} --ipdel {$params->ip} --save";
        }
        return self::$ssh->exec($commands);
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function start($ctid) {
        return self::$ssh->exec(" /usr/sbin/vzctl start {$ctid}");
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function stop($ctid) {
        return self::$ssh->exec(" /usr/sbin/vzctl stop {$ctid}");
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function vrestart($ctid) {
        return self::$ssh->exec(" /usr/sbin/vzctl restart {$ctid}");
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function suspend($ctid) {
        return self::$ssh->exec(" /usr/sbin/vzctl set {$ctid} --disabled yes --save;  /usr/sbin/vzctl stop {$ctid}");
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function unsuspend($ctid) {
        return self::$ssh->exec(" /usr/sbin/vzctl set {$ctid} --disabled no --save;  /usr/sbin/vzctl start {$ctid}");
    }

    /**
     * @param $ctid
     * @param $password
     * @return mixed
     */
    public static function set_password($ctid, $password) {
        return self::$ssh->exec(" /usr/sbin/vzctl start {$ctid};  /usr/sbin/vzctl set {$ctid} --userpasswd root:{$password} --save");
    }

    /**
     * @param $ctid
     * @param $hostname
     * @return mixed
     */
    public static function set_hostname($ctid, $hostname) {
        return self::$ssh->exec(" /usr/sbin/vzctl start {$ctid};  /usr/sbin/vzctl set {$ctid} --hostname {$hostname} --save");
    }

    /**
     * @param $ctid
     * @param $dns1
     * @param $dns2
     * @return mixed
     */
    public static function set_dns($ctid, $dns1, $dns2) {
        return self::$ssh->exec(" /usr/sbin/vzctl set $ctid --nameserver {$dns1}  --nameserver {$dns2} --save;");
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function enable_tuntap($ctid) {
        $commands = "modprobe tun;  /usr/sbin/vzctl set {$ctid} --devnodes net/tun:rw --save
                 /usr/sbin/vzctl set {$ctid} --devices c:10:200:rw --save
                 /usr/sbin/vzctl stop {$ctid}
                 /usr/sbin/vzctl set {$ctid} --capability net_admin:on --save
                 /usr/sbin/vzctl start {$ctid}
                 /usr/sbin/vzctl exec {$ctid} mkdir -p /dev/net
                 /usr/sbin/vzctl exec {$ctid} mknod /dev/net/tun c 10 200";
        return self::$ssh->exec($commands);
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function disable_tuntap($ctid) {
        $commands = " /usr/sbin/vzctl set {$ctid} --devnodes net/tun:none --save
                 /usr/sbin/vzctl set {$ctid} --devices c:10:200:none --save
                 /usr/sbin/vzctl stop {$ctid}
                 /usr/sbin/vzctl set {$ctid} --capability net_admin:off --save
                 /usr/sbin/vzctl start {$ctid}";
        return self::$ssh->exec($commands);
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function enable_ppp($ctid) {
        $commands = " /usr/sbin/vzctl stop {$ctid}
                 /usr/sbin/vzctl set {$ctid} --features ppp:on --save
                 /usr/sbin/vzctl start {$ctid}";
        return self::$ssh->exec($commands);
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function disable_ppp($ctid) {
        $commands = " /usr/sbin/vzctl set {$ctid} --features ppp:off --save;
                 /usr/sbin/vzctl stop {$ctid};
                 /usr/sbin/vzctl start {$ctid};
                ";
        return self::$ssh->exec($commands);
    }

    /**
     * @param $ctid
     * @return bool
     */
    public static function tuntap_status($ctid) {
        if (strlen(self::$ssh->exec(" /usr/sbin/vzctl exec {$ctid} cat /dev/net/tun")) == 48) {
            return true;
        }
        return false;
    }

    /**
     * @param $ctid
     * @return bool
     */
    public static function ppp_status($ctid) {
        if (strlen(self::$ssh->exec(" /usr/sbin/vzctl exec {$ctid} cat /dev/ppp")) == 41) {
            return true;
        }
        return false;
    }

    /**
     * @param $ctid
     * @return mixed
     */
    public static function status($ctid) {
        return self::$ssh->exec("/usr/sbin/vzlist {$ctid} -Ho status");
    }

    public static function runshell($ctid, $cmd) {
        //run a shell command from input and return output, you properply sanitize the command
        return self::$ssh->exec("/usr/sbin/vzlist exec {$ctid} {$cmd}");
    }

    public static function tc_create($params) {
        if (count($params->ips) > 1) {
            foreach ($params->ips as $ip) {
                switch (self::ip_check($ip)) {
                    case 'v4':
                        $fi = "/sbin/tc filter add dev venet0 protocol ip parent 1:0 prio {$params->ctid} u32 match ip dst {$params->ip} flowid 1:{$params->bwin};";
                        $fo = "/sbin/tc filter add dev {$params->interface} protocol ip parent 1:0 prio {$params->ctid} u32 match ip dst {$params->ip} flowid 1:{$params->bwout};";
                        break;
                    case 'v6':
                        $fi = "/sbin/tc filter add dev venet0 protocol ipv6 parent 1:0 prio {$params->ctid} u32 match ipv6 dst {$params->ip} flowid 1:{$params->bwin};";
                        $fo = "/sbin/tc filter add dev {$params->interface} protocol ipv6 parent 1:0 prio {$params->ctid} u32 match ip6 dst {$params->ip} flowid 1:{$params->bwout};";
                        break;
                }
                $commands = "/sbin/tc qdisc add dev venet0 root handle 1: htb;
                    /sbin/tc class add dev venet0 parent 1: classid 1:{$params->bwin} htb rate {$params->bwin}mbit;
                    /sbin/tc qdisc add dev venet0 parent 1:{$params->bwin} handle {$params->bwin}: sfq perturb 10;
                    {$fi}
                    /sbin/tc qdisc add dev {$params->interface} root handle 1: htb;
                    /sbin/tc class add dev {$params->interface} parent 1: classid 1:{$params->bwout} htb rate {$params->bwout}mbit;
                    /sbin/tc qdisc add dev {$params->interface} parent 1:{$params->bwout} handle {$params->bwout}: sfq perturb 10;
                    {$fo}";
            }
        } else {
            switch (self::ip_check($params->ip)) {
                case 'v4':
                    $fi = "/sbin/tc filter add dev venet0 protocol ip parent 1:0 prio {$params->ctid} u32 match ip dst {$params->ip} flowid 1:{$params->bwin}";
                    $fo = "/sbin/tc filter add dev {$params->interface} protocol ip parent 1:0 prio {$params->ctid} u32 match ip dst {$params->ip} flowid 1:{$params->bwout}";
                    break;
                case 'v6':
                    $fi = "/sbin/tc filter add dev venet0 protocol ipv6 parent 1:0 prio {$params->ctid} u32 match ipv6 dst {$params->ip} flowid 1:{$params->bwin}";
                    $fo = "/sbin/tc filter add dev {$params->interface} protocol ipv6 parent 1:0 prio {$params->ctid} u32 match ip6 dst {$params->ip} flowid 1:{$params->bwout}";
                    break;
            }
            $commands = "/sbin/tc qdisc add dev venet0 root handle 1: htb
                /sbin/tc class add dev venet0 parent 1: classid 1:{$params->bwin} htb rate {$params->bwin}mbit
                /sbin/tc qdisc add dev venet0 parent 1:{$params->bwin} handle {$params->bwin}: sfq perturb 10
                {$fi}
                /sbin/tc qdisc add dev {$params->interface} root handle 1: htb
                /sbin/tc class add dev {$params->interface} parent 1: classid 1:{$params->bwout} htb rate {$params->bwout}mbit
                /sbin/tc qdisc add dev {$params->interface} parent 1:{$params->bwout} handle {$params->bwout}: sfq perturb 10
                {$fo}";
        }
        return self::$ssh->exec($commands);
    }

    public static function tc_destroy($params) {
        $commands = "/sbin/tc filter del dev venet0 prio {$params->ctid}
                /sbin/tc filter del dev {$params->interface} prio {$params->ctid}";
        return self::$ssh->exec($commands);
    }

    public static function ip_check($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $result = 'v4';
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $result = 'v6';
        } else {
            $result = 'error';
        }
        return $result;
    }

    public static function enable_fuse($ctid) {
        // pending output for this command to validate results
        self::$ssh->exec(" vzctl set {$ctid} --devnodes fuse:rw --save");
    }

}
