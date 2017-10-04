<?php namespace App\Controls;

use App\IP;
use App\VM;

Class DisableTuntapVMControl extends VMControl  {
    public $name = 'Disable Tuntap';

    public $order = 3;

    public $level = 3;

    public $slug = 'tuntap_disable';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->disableTuntap($vps);

        $vps->update([
            'tuntap' => 0
        ]);
    }

    public function display(VM $vm) {
        return $vm->tuntap == 1;
    }

    public function getIcon() {
        return '<i class="fa fa-times"></i>';
    }
}