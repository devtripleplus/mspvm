<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function generate_mac() {
    global $database;
    while ($i <= 6) {
        $sGen[] = dechex(rand(0, 15));
        $i++;
    }

    $sMac = "00:16:3c:" . $sGen[0] . $sGen[1] . ":" . $sGen[2] . $sGen[3] . ":" . $sGen[4] . $sGen[5];

    /*
     *  We want a unique MAC to we're going to have to save each mac generated and compare it
     */
    if ($sGen == $getfromdatabase) {
        return generate_mac();
    } else {
        return $sMac;
    }
}

function cidrToRange($cidr) {
    $range = array();
    $cidr = explode('/', $cidr);
    //$first_ip = ((ip2long($cidr[0])) & ((-1 << (32 - (int) $cidr[1]))));
    //$last_ip = ((ip2long($cidr[0])) + pow(2, (32 - (int) $cidr[1])) - 1);
    $first_ip = ((ip2long($cidr[0])) & ((-1 << (32 - (int) $cidr[1]))));
    $last_ip = ((ip2long($cidr[0])) + pow(2, (32 - (int) $cidr[1])) - 1);
    //$xrange = range($range[0],$range[2]);
    $xrange = [];

    ;
    $first_ip++;
    while ($first_ip < $last_ip) {
        $real_ip = long2ip($first_ip);
        // echo long2ip($first_ip);
        if (!preg_match('/\.0$/', $real_ip)) { // Don't include IPs that end in .0
            $xrange[] = ($real_ip);
            //  echo long2ip($first_ip);
        }

        $first_ip++;
    }
    array_shift($xrange);
    return $xrange;
}
