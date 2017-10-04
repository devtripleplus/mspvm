<?php namespace App\Controls;

use App\VM;

Class RebootVMControl extends VMControl  {
    public $name = 'Reboot';

    public $order = 1;

    public $level = 1;

    public $slug = 'reboot';

    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->reboot($vps);

        $logEntry = 'The VM has been restarted!';
    }

    public function display(VM $vm) {
        return $vm->online == 1;
    }

    public function getIcon() {
        return '<i class="fa fa-refresh"></i>';
    }
}