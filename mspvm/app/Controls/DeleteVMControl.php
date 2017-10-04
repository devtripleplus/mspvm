<?php namespace App\Controls;

use App\IP;
use App\Log;
use App\VM;

Class DeleteVMControl extends VMControl  {
    public $name = 'Delete';

    public $order = 3;

    public $level = 3;

    public $slug = 'delete';

    /**
     * @TODO: Remove backups
     *
     * @param VM $vps
     * @param $logEntry
     * @param null $data
     * @return mixed
     * @throws \Exception
     */
    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->delete($vps);

        // remove logs
        foreach (Log::where('vps_id', '=', $vps->id)->get() as $entry) {
            $entry->delete();
        }

        // Remove ip associations
        foreach (IP::where('vps_id', '=', $vps->id)->get() as $ip) {
            $ip->update([
                'vps_id' => null
            ]);
        }

        $vps->delete();

        return redirect()->route('admin.vms')->withMessage('VM deletion successful!');
    }

    public function getIcon() {
        return '<i class="fa fa-trash"></i>';
    }
}