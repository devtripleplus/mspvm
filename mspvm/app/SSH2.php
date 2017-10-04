<?php namespace App;

Class SSH2 extends \phpseclib\Net\SSH2 {
    public function exec($command, $callback = null)
    {
        if ($this->host == "localhost") {
            return exec($command);
        }

        return parent::exec($command, $callback);
    }
}