<?php namespace App\Controls;

use App\VM;

Class StartVMControl extends VMControl  {
    public $name = 'Start';

    public $order = 2;

    public $level = 1;

    public $slug = 'start';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->start($vps);

        $vps->update([
           'online' => 1
        ]);
    }

    public function display(VM $vm) {
        return $vm->suspended == 0 && $vm->online == 0;
    }
}