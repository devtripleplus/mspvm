<?php namespace App\Controls;

use App\IP;
use App\VM;

Class DisablePPPVMControl extends VMControl  {
    public $name = 'Disable PPP';

    public $order = 3;

    public $level = 3;

    public $slug = 'ppp_disable';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->disablePPP($vps);

        $vps->update([
            'ppp' => 0
        ]);
    }

    public function display(VM $vm) {
        return $vm->ppp == 1;
    }

    public function getIcon() {
        return '<i class="fa fa-times"></i>';
    }
}