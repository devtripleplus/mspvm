<?php namespace App\Controls;

use App\VM;

Class SerialConsoleVMControl extends VMControl  {
    public $name = 'Serial Console';

    public $order = 3;

    public $level = 1;

    public $slug = 'sconsole';

    public function execute(VM $vps, &$logEntry, $data = null) {

    }
}