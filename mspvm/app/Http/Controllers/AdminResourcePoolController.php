<?php namespace App\Http\Controllers;

use App\Backup;
use App\Controls\DeleteVMControl;
use App\Controls\SuspendVMControl;
use App\Controls\UnsuspendVMControl;
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

Class AdminResourcePoolController extends Controller  {
    public function resource_pools() {
        return view('admin/resourcepools')
            ->with('resource_pools', ResourcePool::all())
            ->with('title', 'Resource Pools');
    }

    public function resource_pool($resource_pool_id) {
        $resource_pool = ResourcePool::find($resource_pool_id);

        if (!$resource_pool) {
            return redirect()->back()->withError('Invalid resource pool!');
        }

        return view('admin/resourcepool')
            ->with('resource_pool', $resource_pool)
            ->with('title', 'Resource Pool');
    }

    public function doSuspendResourcePool($resource_pool_id, SuspendVMControl $control) {
        $resource_pool = ResourcePool::find($resource_pool_id);

        if (!$resource_pool) {
            return redirect()->back()->withError('Invalid resource pool!');
        }

        $logEntry = null;

        foreach ($resource_pool->vms() as $vm) {
            $control->execute($vm, $logEntry);

            Log::create([
                'vps_id' => $vm->id,
                'entry' => $logEntry
            ]);
        }

        $resource_pool->update([
            'status' => 0
        ]);

        return redirect()->back()->withMessage('The resource pool has been suspended!');
    }

    public function doUnsuspendResourcePool($resource_pool_id, UnsuspendVMControl $control) {
        $resource_pool = ResourcePool::find($resource_pool_id);

        if (!$resource_pool) {
            return redirect()->back()->withError('Invalid resource pool!');
        }

        $logEntry = null;

        foreach ($resource_pool->vms() as $vm) {
            $control->execute($vm, $logEntry);

            Log::create([
                'vps_id' => $vm->id,
                'entry' => $logEntry
            ]);
        }

        $resource_pool->update([
            'status' => 1
        ]);

        return redirect()->back()->withMessage('The resource pool has been unsuspended!');
    }

    public function doDeleteResourcePool($resource_pool_id, DeleteVMControl $control) {
        $resource_pool = ResourcePool::find($resource_pool_id);

        if (!$resource_pool) {
            return redirect()->back()->withError('Invalid resource pool!');
        }

        $logEntry = null;

        foreach ($resource_pool->vms() as $vm) {
            $control->execute($vm, $logEntry);

            Log::create([
                'vps_id' => $vm->id,
                'entry' => $logEntry
            ]);
        }

        $resource_pool->delete();

        return redirect()->route('admin.resourcepools')->withMessage('The resource pool has been deleted!');
    }

    public function create() {
        return view('admin/resourcepool_create')
            ->with('packages', Package::lists('name', 'id')->toArray())
            ->with('users', User::lists('username', 'id')->toArray())
            ->with('title', 'New Resource Pools');
    }

    public function doCreate(ResourcePoolRequest $request) {
        $package = Package::find($request->input('package_id'));

        if (!$package) {
            return redirect()->back()->withErrors([
                'Invalid package!'
            ]);
        }

        $user = User::find($request->input('user_id'));

        if (!$user) {
            return redirect()->back()->withErrors([
               'Invalid user!'
            ]);
        }

        ResourcePool::create([
            'disk' => $request->input('disk'),
            'swap' => $request->input('swap'),
            'ram' => $request->input('ram'),
            'ips' => $request->input('ips'),
            'package_id' => $package->id,
            'user_id' => $user->id,
            'status' => 1
        ]);

        return redirect()->route('admin.resourcepools')->withMessage('Success!');
    }
}