<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ssh2 {

    var $ssh;
    var $stream;

    function __construct($host, $port = 22) {
        $this->ssh = new \App\SSH2($host, $port);
    }

    function auth($username, $auth, $private = null, $secret = null) {
        if (is_file($auth) && is_readable($auth) && isset($private)) {
            $this->ssh->login($username, (new \phpseclib\Crypt\RSA())->loadKey(file_get_contents($private)));
        } else {
            $this->ssh->login($username, $auth);
        }

        return true;
    }

    function send($local, $remote, $perm) {
        // TODO
        throw new Exception('Not implemented!');
    }

    function get($remote, $local) {
        // todo
        throw new Exception('Not implemented!');
    }

    function cmd($cmd, $blocking = true) {
        return $this->ssh->exec($cmd);
    }

    // Just an aliasfunction for $this->cmd
    function exec($cmd, $blocking = true) {
        return $this->ssh->exec($cmd);
    }

}