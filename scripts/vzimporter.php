<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo "\033c";
require_once('/var/mspvm/includes/ssh2.class.php');
require_once('/var/mspvm/includes/servers.class.php');
require_once('/var/mspvm/includes/misc.php');
if (!empty($argv[1])) {
    $fn = trim($argv[1]);
} else {
    // $fn = 'e49b48345ef430b815dbc4496c812e9f'; //testing
    die('No file specified');
    ;
}
unlink('/var/mspvm/tmp/' . $fn . '-success.json');
unlink('/var/mspvm/tmp/' . $fn . '-error.json');

//$filename = '/var/mspvm/tmp/' . trim($argv[1]) . '.json';
$filename = '/var/mspvm/tmp/' . $fn . '.json'; //testing purposes only
if (!file_exists($filename)) {
    die('File not found');
}
if (file_exists('/var/mspvm/tmp/' . $fn . '.lock')) {
    die('Script already running');
} else {
    touch('/var/mspvm/tmp/' . $fn . '.lock');
}
$success = [];
$json = json_decode(file_get_contents($filename), true);
$startsfrom = $json['startfrom']; //CTID starts here
$iprange = $json['iprange'];
$source = $json['src'];
$dest = $json['dst'];
$destpass = $json['dstpass'];
$src = new servers($source);
$dst = new servers($dest);
$vps = $json['vps'];
//echo $src->exec('ls -al  /usr/bin/ss*');
//exit();
$success['source'] = $source;

$expect = escapeshellarg(str_replace(['##IP##', '##PASS##'], [$dest, $destpass], file_get_contents('/var/mspvm/scripts/expect.txt')));
$keyput = escapeshellarg(file_get_contents('/var/mspvm/scripts/keyput.txt'));
$solus = escapeshellarg(file_get_contents('/var/mspvm/scripts/configs/solus.repo'));

/* we need to install solus repo to get vzdump */
echo str_pad("SETTING UP SOURCE SERVER", 80, "=", STR_PAD_BOTH) . "\r\n";
$src->exec("echo {$solus} > /etc/yum.repos.d/solus.repo"); //Install Solus repo
echo $src->exec("yum -y install expect vzdump openssh-clients");  //never expected ssh to NOT be installed, might as well install it to be on the safe side

echo str_pad('SETTING UP DESTINATION  SERVER', 80, "=", STR_PAD_BOTH) . "\r\n";
$dst->exec("echo {$solus} > /etc/yum.repos.d/solus.repo"); //Install Solus repo
echo $dst->exec("yum -y install expect vzdump openssh-clients");
/* okay now we have vzdump and expect */

/* generate a link from src to dst */
echo str_pad('GENERATING KEYS FOR SOURCE TO DESTINATION', 80, "=", STR_PAD_BOTH) . "\r\n";
$src->exec("mkdir -p /var/mspvm/backup/{$fn}/;mkdir -p /var/mspvm/data;cd /var/mspvm/data/;echo {$expect} > expector.exp;echo {$keyput} > keyput.sh;expect expector.exp;rm -f expector.exp");
$dst->exec("mkdir -p /var/mspvm/backup/{$fn}/;");

echo str_pad("STARTING BACKUP", 80, "=", STR_PAD_BOTH) . "\r\n";
$newid = $startsfrom;
//$vps = array_slice($vps, 0, 2);
$total = count($vps);
foreach ($vps as $k => $v) {
    $newid++;
    $counter = $k + 1;
    $ctid = $v['ctid'];
    echo str_pad("VPS ( {$counter}/{$total} ) ", 80, "=", STR_PAD_BOTH) . "\r\n";

    $command = "vzdump --compress --dumpdir /var/mspvm/backup/{$fn}/ --suspend {$ctid}";
    echo str_pad("BACKING UP {$ctid} STEP 1/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    echo $src->exec($command);

    echo str_pad("SENDING {$ctid} TO DESTINATION STEP 2/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    $command = "scp /var/mspvm/backup/{$fn}/vzdump-{$ctid}.tgz {$dest}:/var/mspvm/backup/{$fn}/";

    echo $src->exec($command);
    echo 'Completed' . "\r\n";



    echo str_pad("RESTORING {$ctid} AS {$newid} STEP 3/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    $command = "vzdump --restore /var/mspvm/backup/{$fn}/vzdump-{$ctid}.tgz {$newid} 2>&1";
    $ret = $dst->exec($command);
    echo $ret;
    if (strstr($ret, 'unable to restore')) {
        $error[] = ['oldid' => $ctid, 'newid' => $newid, 'msg' => $ret];
        continue;
    }

    $ips = $dst->exec("vzlist -oip --json {$newid}");
    //echo ("vzlist -oip --json {$newid} \r\n");
    //var_dump($ips);
    $ips = json_decode($ips, true);
    //   print_r($ips);
    echo str_pad("REMOVING IPS FROM {$ctid}::{$newid} STEP 4/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    if (empty($ips)) {
        $error[] = ['oldid' => $ctid, 'newid' => $newid, 'msg' => 'No Ips Assigned'];
    } else {
        //   print_r($ips);
        foreach ($ips[0]['ip'] as $ip) {
            $command = " /usr/sbin/vzctl set {$newid} --ipdel {$ip} --save";
            $ret = $dst->exec($command);
            echo $ret;
        }
    }
    echo str_pad("STARTING UP {$ctid}::{$newid} STEP 5/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    $command = "vzctl start {$newid} 2>&1 ";
    $ret = $dst->exec($command);
    echo $ret;
    if (stristr($ret, 'unable to')) {
        $error[] = ['oldid' => $ctid, 'newid' => $newid, 'msg' => $ret];
        continue;
    }
    echo $ret;
    $newip = array_shift($iprange);
    echo str_pad("ASSIGNING NEW IP TO {$ctid}::{$newid} STEP 6/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    $command = " /usr/sbin/vzctl set {$newid} --ipadd {$newip} --save";
    echo $dst->exec($command);
    echo str_pad("TESTING CONNECTIVITY {$ctid}::{$newid} STEP 7/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    $command = " /usr/sbin/vzctl exec {$newid} ping 8.8.8.8 -c 5";
    $ret = $dst->exec($command);
    echo $ret;
    if (stristr($ret, '100% packet loss')) {
        $error[] = ['oldid' => $ctid, 'newid' => $newid, 'msg' => $ret];
        continue;
    }
    $success['vps'] = ['old' => $ctid, 'new' => $newid];
    echo str_pad("DELETING BACKUP FILES {$ctid}::{$newid} STEP 8/8", 80, "=", STR_PAD_BOTH) . "\r\n";
    $src->exec("rm -rf /var/mspvm/backup/{$fn}/vzdump-{$ctid}.tgz");
    $dst->exec("rm -rf /var/mspvm/backup/{$fn}/vzdump-{$ctid}.tgz");
    //100% packet loss
    // echo $command;
    //Unable to start
}
file_put_contents('/var/mspvm/tmp/' . $fn . '-success.json', json_encode($success));
file_put_contents('/var/mspvm/tmp/' . $fn . '-error.json', json_encode($error));
unlink('/var/mspvm/tmp/' . $fn . '.json');
//print_r($error);
unlink('/var/mspvm/tmp/' . $fn . '.lock'); //remove lock file
//
//echo $src->exec('ssh'); 
echo '##COMPLETE##';

