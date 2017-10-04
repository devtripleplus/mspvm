<?php namespace App\Controls;

use App\Template;
use App\VM;

Class RebuildVMControl extends VMControl  {
    public $name = 'Rebuild VM';

    public $order = 3;

    public $level = 1;

    public $slug = 'rebuild';

    public function validate($request, &$validator) {
        $validator = [
            'password' => 'required|min:8',
            'hostname' => 'required',
            'template_id' => 'required',
            'disk' => 'required|numeric|min:0',
            'inode_limit' => 'required|numeric|min:0',
            'burst' => 'required|numeric|min:0',
            'ram' => 'required|numeric|min:0',
            'cpus' => 'required|numeric|min:0',
            'cpu_units' => 'required|numeric|min:0',
            'cpu_limit' => 'required|numeric|min:0',
            'bandwith_limit' => 'required|numeric|min:0'
        ];
    }

    public function form(VM $vm) {
        return [
            [
                'label' => 'Hostname',
                'value' => $vm->hostname
            ],
            [
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password'
            ],
            [
                'label' => 'Template',
                'name' => 'template_id',
                'type' => 'select',
                'options' => Template::lists('name', 'id')->toArray()
            ],
            [
                'label' => 'DNS 1',
                'name' => 'dns_1',
                'value' => '8.8.8.8'
            ],
            [
                'label' => 'DNS 2',
                'name' => 'dns_2',
                'value' => '8.8.4.4'
            ],
            [
                'label' => 'Disk (GB)',
                'name' => 'disk',
                'value' => $vm->disk
            ],
            [
                'label' => 'Inode Limit',
                'name' => 'inode_limit',
                'value' => $vm->inode_limit
            ],
            [
                'label' => 'RAM (MB)',
                'name' => 'ram',
                'value' => $vm->ram
            ],
            [
                'label' => 'Burst (MB)',
                'name' => 'burst',
                'value' => $vm->burst
            ],
            [
                'label' => 'Swap (MB)',
                'name' => 'swap',
                'value' => $vm->swap
            ],
            [
                'label' => 'CPU Units',
                'name' => 'cpu_units',
                'value' => $vm->cpu_units
            ],
            [
                'label' => 'CPU Limit',
                'name' => 'cpu_limit',
                'value' => $vm->cpu_limit
            ],
            [
                'label' => 'CPUs',
                'name' => 'cpus',
                'value' => $vm->cpus
            ],
            [
                'label' => 'Bandwidth Limit (GB)',
                'name' => 'bandwith_limit',
                'value' => $vm->bandwith_limit
            ]
        ];
    }

    /**
     * @TODO SANITIZE data
     *
     * @param VM $vps
     * @param $logEntry
     * @param null $data
     */
    public function execute(VM $vm, &$logEntry, $data = null) {
        $template = Template::find($data['template_id']);

        $vm->update(
            [
                'disk' => $data['disk'],
                'inode_limit' => $data['inode_limit'],
                'ram' => $data['ram'],
                'burst' => $data['burst'],
                'swap' => $data['swap'],
                'cpu_units' => $data['cpu_units'],
                'cpu_limit' => $data['cpu_limit'],
                'cpus' => $data['cpus'],
                'bandwith_limit' => $data['bandwith_limit'],

                'suspended' => 0,
                'online' => 1,

                'template_id' => $data['template_id'],

                'hostname' => $data['hostname'],
                'dns1' => $data['dns_1'],
                'dns2' => $data['dns_2'],

                'tuntap' => 0,
                'ppp' => 0,
                'fuse' => 0
            ]
        );

        try {
            $vm->virtualization()->rebuild(
                $vm,
                $template,
                $data['password'],
                $data['dns_1'],
                $data['dns_2']
            );
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        $logEntry = 'The VM has been rebuilt!';

        return redirect()->back()->withMessage('The VM has been rebuilt!');
    }

    public function display(VM $vm) {
        return $vm->suspended == 0;
    }

    public function getIcon() {
        return '<i class="fa fa-circle-o-notch"></i>';
    }
}