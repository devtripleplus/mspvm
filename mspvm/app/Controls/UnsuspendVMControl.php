<?php namespace App\Controls;

use App\VM;

Class UnsuspendVMControl extends VMControl  {
    public $name = 'Unsuspend';

    public $order = 3;

    public $level = 3;

    public $slug = 'unsuspend';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->unsuspend($vps);

        $logEntry = 'The VM has been unsuspended!';

        $vps->update([
            'suspended' => 0,
            'online' => 1
        ]);
    }

    public function display(VM $vm) {
        return $vm->suspended == 1;
    }

    public function getIcon() {
        return '<i class="fa fa-plus-circle"></i>';
    }
}