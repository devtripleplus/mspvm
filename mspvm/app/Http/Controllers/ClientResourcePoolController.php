<?php namespace App\Http\Controllers;

use App\Backup;
use App\Http\Requests\ResourcePoolRequest;
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

Class ClientResourcePoolController extends Controller  {
    public function resource_pools() {
        return view('resourcepools')
            ->with('resource_pools', ResourcePool::where('user_id', $this->user->id)->get())
            ->with('title', 'Resource Pools');
    }

    /**
     * @TODO Make IP dropdown multiselect
     *
     * @return $this
     */
    public function createFromResourcePool($pool_id) {
        $pool = ResourcePool::find($pool_id);

        if (!$pool || $pool->user_id != $this->user->id) {
            return redirect()->back()->withErrors([
               'Invalid resource pool!'
            ]);
        }

        return view('vm_create_from_resource_pool')
            ->with('resource_pool', $pool)
            ->with('servers', Server::lists('name', 'id')->toArray())
            ->with('templates', Template::lists('name', 'id')->toArray())
            ->with('ips', IP::where('vps_id', '=', '0')->lists('ip_address', 'id')->toArray())
            ->with('title', 'New VM');
    }

    public function doCreateFromResourcePool($pool_id, VMRequest $request) {
        $resource_pool = ResourcePool::find($pool_id);

        if (!$resource_pool || $resource_pool->user_id != $this->user->id) {
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
                'user_id' => $this->user->id,
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
}