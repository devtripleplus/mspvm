<?php namespace App\Http\Controllers;

use App\Backup;
use App\Http\Requests\VMRequest;
use App\IP;
use App\Log;
use App\Package;
use App\ResourcePool;
use App\Server;
use App\Service\VMBuilderService;
use App\Template;
use App\User;
use App\VM;
use Illuminate\Http\Request;

Class AdminVMController extends Controller  {
    public function vms() {
        return view('admin/vms')
            ->with('servers', VM::all())
            ->with('extra', is_array($this->request->input('select')) ? $this->request->input('select') : [])
            ->with('title', 'VMs');
    }

    public function create() {
        return view('admin/vm_create')
            ->with('packages', Package::lists('name', 'id')->toArray())
            ->with('servers', Server::lists('name', 'id')->toArray())
            ->with('templates', Template::lists('name', 'id')->toArray())
            ->with('users', User::lists('username', 'id')->toArray())
            ->with('ips', IP::where('vps_id', '=', '0')->lists('ip_address', 'id')->toArray())
            ->with('title', 'New VM');
    }

    /**
     * @TODO Make IP dropdown multiselect
     *
     * @return $this
     */
    public function createFromResourcePool($pool_id) {
        return view('admin/vm_create_from_resource_pool')
            ->with('resource_pool', ResourcePool::find($pool_id))
            ->with('servers', Server::lists('name', 'id')->toArray())
            ->with('templates', Template::lists('name', 'id')->toArray())
            ->with('ips', IP::where('vps_id', '=', '0')->lists('ip_address', 'id')->toArray())
            ->with('title', 'New VM');
    }

    public function doCreate(VMRequest $request) {
        $package = Package::find($request->input('package_id'));

        if (!$package) {
            return redirect()->back()->withErrors([
                'Invalid package!'
            ]);
        }

        $server = Server::find($request->input('server_id'));

        if (!$server) {
            return redirect()->back()->withErrors([
                'Invalid server!'
            ]);
        }

        $template = Template::find($request->input('template_id'));

        if (!$template) {
            return redirect()->back()->withErrors([
                'Invalid template!'
            ]);
        }

        $ip = IP::find($request->input('ip_id'));

        if (!$ip || $ip->vps_id) {
            return redirect()->back()->withErrors([
                'Invalid IP!'
            ]);
        }

        $vm = VM::create(array_merge(
            [
                'disk' => $package->disk,
                'inode_limit' => $package->inode_limit,
                'ram' => $package->ram,
                'burst' => $package->burst,
                'swap' => $package->swap,
                'cpu_units' => $package->cpu_units,
                'cpu_limit' => $package->cpu_limit,
                'cpus' => $package->cpus,
                'bandwith_limit' => $package->bandwith_limit,
                'network_speed' => $package->network_speed,

                'suspended' => 0,
                'online' => 1,

                'package_id' => $request->input('package_id'),
                'server_id' => $request->input('server_id'),
                'template_id' => $request->input('template_id'),
                'primary_ip' => $ip->ip_address,
                'user_id' => $request->input('user_id'),

                'tuntap' => 0,
                'ppp' => 0,
                'fuse' => 0
            ],
            $request->only('name', 'hostname', 'dns1', 'dns2')
        ));

        if (!$vm) {
            return redirect()->back()->withErrors('Something went wrong while creating the VM')->withInput();
        }

        try {
            $identifier = VMBuilderService::vm(
                $vm,
                $server,
                $template,
                $request->input('password'),
                $ip,
                $request->input('dns1'),
                $request->input('dns2')
            );

            $vm->update([
                'virt_identifier' => $identifier
            ]);

            $ip->update([
               'vps_id' => $vm->id
            ]);

            Log::create([
                'vps_id' => $vm->id,
                'user_id' => $this->user->id,
                'command' => 'create',
                'entry' => 'The VM has been created!'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getFile().$e->getLine().$e->getMessage());
        }

        return redirect()->back()->withMessage('Success!');
    }

    public function doCreateFromResourcePool($pool_id, VMRequest $request) {
        $resource_pool = ResourcePool::find($pool_id);

        if (!$resource_pool) {
            return redirect()->back()->withErrors([
                'Invalid resource pool!'
            ])->withInput();
        }

        $server = Server::find($request->input('server_id'));

        if (!$server) {
            return redirect()->back()->withErrors([
                'Invalid server!'
            ])->withInput();
        }

        $template = Template::find($request->input('template_id'));

        if (!$template) {
            return redirect()->back()->withErrors([
                'Invalid template!'
            ])->withInput();
        }

        $ip = IP::find($request->input('ip_id'));

        if (!$ip || $ip->vps_id) {
            return redirect()->back()->withErrors([
                'Invalid IP!'
            ])->withInput();
        }

        try {
            $package = $resource_pool->mockPackage(
                $request->input('disk'),
                $request->input('ram'),
                $request->input('swap'),
                $request->input('ip')
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        $vm = VM::create(array_merge(
            [
                'disk' => $package->disk,
                'inode_limit' => $package->inode_limit,
                'ram' => $package->ram,
                'burst' => $package->burst,
                'swap' => $package->swap,
                'cpu_units' => $package->cpu_units,
                'cpu_limit' => $package->cpu_limit,
                'cpus' => $package->cpus,
                'bandwith_limit' => $package->bandwith_limit,
                'network_speed' => $package->network_speed,

                'suspended' => 0,
                'online' => 1,

                'package_id' => null,
                'server_id' => $request->input('server_id'),
                'template_id' => $request->input('template_id'),
                'primary_ip' => $ip->ip_address,
                'user_id' => $request->input('user_id'),
                'resource_pool_id' => $resource_pool->id,

                'tuntap' => 0,
                'ppp' => 0,
                'fuse' => 0
            ],
            $request->only('name', 'hostname', 'dns1', 'dns2')
        ));

        if (!$vm) {
            return redirect()->back()->withErrors('Something went wrong while creating the VM')->withInput();
        }

        try {
            $identifier = VMBuilderService::vm(
                $vm,
                $server,
                $template,
                $request->input('password'),
                $ip,
                $request->input('dns1'),
                $request->input('dns2')
            );

            $vm->update([
                'virt_identifier' => $identifier
            ]);

            $ip->update([
                'vps_id' => $vm->id
            ]);

            Log::create([
                'vps_id' => $vm->id,
                'user_id' => $this->user->id,
                'command' => 'create',
                'entry' => 'The VM has been created!'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->back()->withMessage('Success!');
    }

    public function doSetTCControl($vm_id, Request $request) {
        /**
         * @var $vm VM
         */
        $vm = VM::find($vm_id);

        if (!$vm) {
            return redirect()->back()->withErrors([
                'Invalid VM!'
            ]);
        }

        $vm->virtualization()->setTCControl(
            $vm,
            intval($request->input('network_speed')),
            intval($request->input('network_speed'))
        );

        $vm->update([
            'network_speed' => intval($request->input('network_speed'))
        ]);

        return redirect()->back()->withMessage('Success!');
    }

    public function assignIp($vm_id) {
        $ip = IP::find($this->request->input('ip_id'));

        if (!$ip && $ip->hasVM()) {
            return redirect()->back()->withErrors([
                'Invalid IP!'
            ]);
        }

        /**
         * @var $vm VM
         */
        $vm = VM::find($vm_id);

        if (!$vm) {
            return redirect()->back()->withErrors([
                'Invalid VM!'
            ]);
        }

        $vm->virtualization()->addIP($vm, $ip);

        $vm->virtualization()->setTCControl($vm,
            $vm->network_speed,
            $vm->network_speed,
            [
                $ip->ip_address
            ]
        );

        $ip->update([
            'vps_id' => $vm->id
        ]);

        Log::create([
            'vps_id' => $vm->id,
            'command' => 'addip',
            'entry' => 'A new IP address has been assigned to the VM: <b>'.$ip->ip_address.'</b>'
        ]);

        return redirect()->back()->withMessage('Success!');
    }

    public function removeIp($ip_id) {
        $ip = IP::find($ip_id);

        if (!$ip && !$ip->hasVM()) {
            return redirect()->back()->withErrors([
               'Invalid IP!'
            ]);
        }

        /**
         * @var $vm VM
         */
        $vm = VM::find($ip->vps_id);

        if ($vm->primary_ip == $ip->ip_address) {
            return redirect()->back()->withErrors([
               'You can not remove the primary IP address!'
            ]);
        }

        $vm->virtualization()->removeIP($vm, $ip);

        $ip->update([
           'vps_id' => null
        ]);

        Log::create([
            'vps_id' => $vm->id,
            'command' => 'removeip',
            'entry' => 'An IP address has been removed from the VM: <b>'.$ip->ip_address.'</b>'
        ]);

        return redirect()->back()->withMessage('Success!');
    }

    public function doAjaxToggleBackupMethod($vm_id) {
        $vm = VM::find($vm_id);

        if (!$vm) {
            return json_encode([
                'error' => 1,
               'message' => 'Invalid VM!'
            ]);
        }

        $method = array_first(app('backup'), function ($i, $method) {
            if (get_class($method) == $this->request->input('method')) {
                return true;
            }

            return false;
        });

        if (!$method) {
            return json_encode([
                'error' => 1,
                'message' => 'Invalid backup method!'
            ]);
        }

        if ($vm->hasBackupMethodEnabled($this->request->input('method'))) {
            $vm->disableBackupMethod($this->request->input('method'));
        } else {
            $vm->enableBackupMethod($this->request->input('method'));
        }

        return json_encode([
            'error' => 0,
            'message' => (string) view('misc/backup/method_panel', [
                'vm' => $vm,
                'method' => $method
            ])
        ]);
    }

    public function doAjaxUpdateBackupMethod($vm_id) {
        $vm = VM::find($vm_id);

        if (!$vm) {
            return json_encode([
                'error' => 1,
                'message' => 'Invalid VM!'
            ]);
        }

        $method = array_first(app('backup'), function ($i, $method) {
            if (get_class($method) == $this->request->input('method')) {
                return true;
            }

            return false;
        });

        if (!$method) {
            return json_encode([
                'error' => 1,
                'message' => 'Invalid backup method!'
            ]);
        }

        $data = $this->request->except('_token', 'method');

        if (($error = $method->validateConfigForm($vm, $data)) != true) {
            return json_encode([
                'error' => 1,
                'message' => $error
            ]);
        }

        // UPDATE
        $vm->updateBackupMethodSettings($method, $data);

        return json_encode([
            'error' => 0,
            'message' => (string) view('misc/backup/method_panel', [
                'vm' => $vm,
                'method' => $method
            ])
        ]);
    }

    public function manage($vm_id) {
        $vm = VM::find($vm_id);

        return view('admin/vm')
            ->with('title', $vm->getName())
            ->with('vm', $vm)
            ->with('logs', Log::where('vps_id', '=', $vm->id)->orderBy('created_at', 'DESC')->limit(15)->get())
            ->with('controls', app('control')->getVMControls($vm, 3))
            ->with('ips', IP::where('vps_id', '=', $vm->id)->get())
            ->with('available_ips', IP::where('vps_id', '=', '0')->lists('ip_address', 'id')->toArray())
            ->with('backup_methods', app('backup'))
            ->with('backups', Backup::where('vps_id', '=', $vm->id)->orderBy('id', 'DESC')->get());
    }
}