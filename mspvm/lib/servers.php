<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class servers {

    public static $salt = 'asd123cvcsdhfgasdgds';
    var $serverIP;
    var $ssh;

    function __construct($nodeID = '') {
        //we should get the node ID here
        //   echo "statuc?";
        return $this->tconnect($nodeID);
    }

    function connect($nodeID) {

    }

    function nodeInfo() {
        $uptime = escapeshellarg(file_get_contents('/var/mspvm/scripts/uptime.txt'));

        $this->exec("mkdir -p /var/mspvm/data;cd /var/mspvm/data/;echo {$uptime} > uptime.php;");
        return $this->exec('/usr/bin/php /var/mspvm/data/uptime.php 2>&1 ');
    }

    function openvz_compact($serverIP) {

        $compact = escapeshellarg(file_get_contents('./scripts/compact.txt'));

        $this->exec("mkdir -p /var/mspvm/data;cd /var/mspvm/data/;echo {$compact} > compact.sh;");
        return $this->exec('/bin/sh /var/mspvm/data/compact.sh 2>&1 ');
    }

    /* test connect */

    function tconnect($serverIP) {
        $salt = self::$salt;
        $fn = '/var/mspvm/keys/' . sha1($serverIP . $salt);
        try {
            $ssh = new ssh2($serverIP);
        } catch (\ErrorException $e) {
            return false;
        }
        if (!file_exists($fn . '.pub')) {
            return false;
        }
        if ($ssh->auth("root", $fn . '.pub', $fn . '.txt', '')) {
            $this->ssh = $ssh;
        } else {
            return false;
        }
    }

    function exec($command) {
        //we REALLY should log information here
        /*
         * Logs should have user IP,user ID, $command run,$target IP
         */
        if (is_object($this->ssh)) {
            return $this->ssh->exec($command);
        }
        return false;
    }

    public static function addServer($server, $password) {
        $salt = self::$salt;

        $commandList = <<<SSH
rm -f ~/.ssh/id_dsa;
ssh-keygen -t dsa -N "" -f ~/.ssh/id_dsa;
cat ~/.ssh/id_dsa.pub >> ~/.ssh/authorized_keys;
SSH;




        $ssh = new \App\SSH2($server);
        $ssh->login("root", $password);
        $ssh->exec($commandList);
        $id_dsa = $ssh->exec('cat ~/.ssh/id_dsa');
        $id_dsa_pub = $ssh->exec('cat ~/.ssh/id_dsa.pub');
        $fn = '/var/mspvm/keys/' . sha1($server . $salt);
        if (!empty($id_dsa)) {
            file_put_contents($fn . '.txt', $id_dsa);
            file_put_contents($fn . '.pub', $id_dsa_pub);
        }
    }

}
