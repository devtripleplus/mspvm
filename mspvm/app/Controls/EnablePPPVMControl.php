<?php namespace App\Controls;

use App\IP;
use App\VM;

Class EnablePPPVMControl extends VMControl  {
    public $name = 'Enable PPP';

    public $order = 3;

    public $level = 3;

    public $slug = 'ppp_enable';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->enablePPP($vps);

        $vps->update([
            'ppp' => 1
        ]);
    }

    public function display(VM $vm) {
        return $vm->ppp == 0;
    }

    public function getIcon() {
        return '<i class="fa fa-plus"></i>';
    }
}