<?php namespace App\Controls;

use App\IP;
use App\VM;

Class EnableTuntapVMControl extends VMControl  {
    public $name = 'Enable Tuntap';

    public $order = 3;

    public $level = 3;

    public $slug = 'tuntap_enable';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->enableTuntap($vps);

        $vps->update([
            'tuntap' => 1
        ]);
    }

    public function display(VM $vm) {
        return $vm->tuntap == 0;
    }

    public function getIcon() {
        return '<i class="fa fa-plus"></i>';
    }
}