<?php namespace App\Controls;

use App\IP;
use App\VM;

Class EnableFuseVMControl extends VMControl  {
    public $name = 'Enable Fuse';

    public $order = 3;

    public $level = 3;

    public $slug = 'fuse_enable';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->enableFuse($vps);

        $vps->update([
            'fuse' => 1
        ]);
    }

    public function display(VM $vm) {
        return $vm->fuse == 0;
    }

    public function getIcon() {
        return '<i class="fa fa-plus"></i>';
    }
}