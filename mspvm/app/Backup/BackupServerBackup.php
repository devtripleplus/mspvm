<?php namespace App\Backup;

use App\BackupServer;
use App\VM;

Class BackupServerBackup extends BackupMethod {
    public function getID() {
        return static::class;
    }

    public function getName() {
        return 'Backup Server';
    }

    public function getDescription() {
        return 'Backups saved to an off-site backup server.';
    }

    public function restore(VM $vm) {

    }

    public function configForm(VM $vm, $data) {
        return view('misc/backup/server', array_merge($data, [
            'servers' => array_merge([
                0 => 'Select Random Server'
            ], BackupServer::lists('host', 'id')->toArray())
        ]));
    }

    public function create(VM $vm) {
        $vm->virtualization()->execute();
    }
}