<?php namespace App\Controls;

use App\VM;

Class HostnameVMControl extends VMControl  {
    public $name = 'Hostname';

    public $order = 3;

    public $level = 1;

    public $slug = 'hostname';

    public function form(VM $vm) {
        return [
          [
              'label' => 'Hostname'
          ]
        ];
    }

    public function validate($request, &$rules) {
        $rules = [
          'hostname' => 'required'
        ];
    }

    /**
     *
     * @TODO SANITIZE HOST!
     *
     * @param VM $vps
     * @param $logEntry
     * @param null $data
     */
    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->setHostname($vps, $data['hostname']);

        $vps->update([
           'hostname' => array_get($data, 'hostname', $vps->hostname)
        ]);

        $logEntry = 'Hostname has been changed to <b>'.htmlentities($data['hostname']).'</b>';
    }

    public function getIcon() {
        return '<i class="fa fa-terminal"></i>';
    }
}