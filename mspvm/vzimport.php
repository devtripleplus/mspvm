<?php
session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('/var/mspvm/includes/ssh2.class.php');
require_once('/var/mspvm/includes/servers.class.php');
require_once('/var/mspvm/includes/misc.php');
$importid = filter_input(INPUT_GET, 'importid', FILTER_SANITIZE_STRING);

$source['ip'] = '198.23.243.35';
$source['pass'] = 'mspserver123*';
$dest['ip'] = '185.137.130.43';
$dest['pass'] = 'mspserver123*';
$newrange = cidrToRange('185.117.23.0/28');

$inuse = ['198.23.243.35'];  //we need to do some sanity checks here, like which IPS have already been assigned or not able to use

/* Normally we do not need this but for testing purposes
 * Script should always have a localhost key in store to perform commands */
$localhost = new servers('127.0.0.1');
if (!$localhost->exec('ls')) {
    // This section should not be necessary, we should always have a localhost keypair
    servers::addServer('127.0.0.1', 'mspserver123*');
    $localhost = new servers('127.0.0.1');
}


/* End testing */

$newrange = array_diff($newrange, $inuse);
//var_dump($newrange);
$startfrom = 400;

$configname = md5($source['ip'] . $dest['ip']);

if (!empty($importid)) {

    $str = $localhost->exec("tail /var/mspvm/tmp/{$configname}.out -n 500 | tac");
    if (strpos($str, '##COMPLETE##') == 0):
        ?>

        <head>
            <meta http-equiv="refresh" content="30">
        </head>
        <p>
            We REALLY should have this inside a textbox.  This page will refresh every 30 seconds till the import is completed
        </p>
        <?php
    endif;
    echo nl2br($str);

    exit();
}

$src = new servers($source['ip']);
if (!$src->exec('ls')) {
    // echo 'add server';
    servers::addServer($source['ip'], $source['pass']);
    $src = new servers($source['ip']);
}


$dst = new servers($dest['ip']);
if (!$dst->exec('ls')) {
    // echo 'add server';
    servers::addServer($dest['ip'], $dest['pass']);
    $dst = new servers($dest['ip']);
}

$json = $src->exec('vzlist -a -o ctid,ip,ostemplate,disabled --json');
//echo $json;
$json = json_decode($json);
//print_r($json);
if (count($newrange) <= count($json)) {
    die('Not enough IP');
}
foreach ($json as $j) {
    $out['vps'][] = ['ctid' => $j->ctid, 'userid' => '1'];
}
$out['startfrom'] = 400;
$out['iprange'] = $newrange;
$out['src'] = $source['ip'];
$out['dst'] = $dest['ip'];
$out['dstpass'] = $dest['pass'];

/*
 * We can add in a php checkbox form here to check for VPS's that you want
 * 
 * 
 */
$json_out = json_encode($out);
file_put_contents("/var/mspvm/tmp/" . $configname . '.json', $json_out);
//nohup some_command &> nohup2.out&
touch("/var/mspvm/tmp/{$configname}.out ");
$command = "nohup php /var/mspvm/scripts/vzimporter.php {$configname} &> /var/mspvm/tmp/{$configname}.out &";
//echo $command;
flush();
$localhost->exec($command);
//echo '123';
header('Location:' . $_SERVER['PHP_SELF'] . '?importid=' . $configname);
//echo $json_out;
//$ssh = new ssh2($server);
//$ssh->auth("root", $password);
