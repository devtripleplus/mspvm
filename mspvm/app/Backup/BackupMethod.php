<?php namespace App\Backup;

use App\VM;

Abstract class BackupMethod {
    public abstract function getID();

    public abstract function getName();

    public abstract function getDescription();

    public abstract function create(VM $vm);

    public abstract function restore(VM $vm);

    /**
     * @param VM $vm
     * @param $data
     * @return bool|string
     */
    public function configForm(VM $vm, $data) {
        return false;
    }

    public function validateConfigForm(VM $vm, $data) {
        return true;
    }

    public function hasConfigForm(VM $vm) {
        return $this->getConfigForm($vm);
    }
    
    public function getConfigForm(VM $vm) {
        return $this->configForm($vm, [
            'settings' => array_get($vm->getBackupMethods(), $this->getID(), [])
        ]);
    }
}