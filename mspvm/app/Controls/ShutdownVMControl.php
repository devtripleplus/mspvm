<?php namespace App\Controls;

use App\VM;

Class ShutdownVMControl extends VMControl  {
    public $name = 'Shutdown';

    public $order = 2;

    public $level = 1;

    public $slug = 'shutdown';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->stop($vps);

        $vps->update([
           'online' => 0
        ]);
    }

    public function display(VM $vm) {
        return $vm->suspended == 0 && $vm->online == 1;
    }

    public function getIcon() {
        return '<i class="fa fa-power-off"></i>';
    }
}