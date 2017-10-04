<?php namespace App\Driver;

use App\Controls\BootVMControl;
use App\Controls\DeleteVMControl;
use App\Controls\DisablePPPVMControl;
use App\Controls\DisableTuntapVMControl;
use App\Controls\EnableFuseVMControl;
use App\Controls\EnablePPPVMControl;
use App\Controls\EnableTuntapVMControl;
use App\Controls\HostnameVMControl;
use App\Controls\RebootVMControl;
use App\Controls\RebuildVMControl;
use App\Controls\RootPasswordVMControl;
use App\Controls\SerialConsoleVMControl;
use App\Controls\ShutdownVMControl;
use App\Controls\StartVMControl;
use App\Controls\SuspendVMControl;
use App\Controls\UnsuspendVMControl;
use App\VM;

Class ControlDriver {
    public function getControls() {
        return [
            new SuspendVMControl(),
            new UnsuspendVMControl(),
            new ShutdownVMControl(),
            new StartVMControl(),
            new RebootVMControl(),
            //new SerialConsoleVMControl(),
            new RootPasswordVMControl(),
            new HostnameVMControl(),
            new EnableFuseVMControl(),
            new EnableTuntapVMControl(),
            new DisableTuntapVMControl(),
            new EnablePPPVMControl(),
            new DisablePPPVMControl(),
            new DeleteVMControl(),
            new RebuildVMControl()
        ];
    }

    /**
     * @TODO Take order into consideration
     *
     * @param int $level
     * @return array
     */
    public function getVMControls(VM $vm, $level = 1) {
        $controls = [];
        foreach ($this->getControls() as $control) {
            if ($control->getLevel() <= $level && $control->display($vm)) {
                $controls[] = $control;
            }
        }

        return $controls;
    }
}