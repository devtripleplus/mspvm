<?php namespace App\Controls;

use App\VM;

Class RootPasswordVMControl extends VMControl  {
    public $name = 'Root Password';

    public $order = 3;

    public $level = 1;

    public $slug = 'password';

    public function validate($request, &$validator) {
        $validator = [
            'password' => 'required|confirmed|min:8'
        ];
    }

    public function form(VM $vm) {
        return [
            [
                'label' => 'Password',
                'type' => 'password'
            ],
            [
                'label' => 'Password Confirmation',
                'name' => 'password_confirmation',
                'type' => 'password'
            ]
        ];
    }

    /**
     * @TODO SANITIZE password
     *
     * @param VM $vps
     * @param $logEntry
     * @param null $data
     */
    public function execute(VM $vps, &$logEntry, $data = null) {
        $vps->virtualization()->setPassword($vps, $data['password']);

        $logEntry = 'The root password has been reset!';
    }

    public function display(VM $vm) {
        return $vm->suspended == 0;
    }

    public function getIcon() {
        return '<i class="fa fa-unlock-alt"></i>';
    }
}