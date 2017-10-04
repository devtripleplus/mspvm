<?php namespace App\Backup;

use App\VM;
use Carbon\Carbon;

Class FTPServerBackup extends BackupMethod {
    public function getID() {
        return static::class;
    }

    public function getName() {
        return 'FTP Server';
    }

    public function getDescription() {
        return 'Backups saved to an external server of your choice.';
    }

    public function restore(VM $vm) {
        $vm->virtualization()->getBackup();
    }

    public function create(VM $vm) {
        $vm->virtualization(); // power off
        sleep(5); // sleep for 5 seconds

        $file = storage_path('tmp/'.$vm->getVirtID().'-'.Carbon::now()->toDateTimeString().'.zip');

        $vm->virtualization()->createBackup($file, 'scp FILE user@host');

        $vm->virtualization(); // power on
    }

    public function configForm(VM $vm, $data) {
        return view('misc/backup/ftp', $data);
    }
}