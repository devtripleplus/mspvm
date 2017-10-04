<?php namespace App\Controls;

use App\VM;

Class SuspendVMControl extends VMControl  {
    public $name = 'Suspend';

    public $order = 3;

    public $level = 3;

    public $slug = 'suspend';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->suspend($vps);

        $logEntry = 'The VM has been suspended!';

        $vps->update([
            'suspended' => 1,
            'online' => 0
        ]);
    }

    public function display(VM $vm) {
        return $vm->suspended == 0;
    }

    public function getIcon() {
        return '<i class="fa fa-times-circle"></i>';
    }
}