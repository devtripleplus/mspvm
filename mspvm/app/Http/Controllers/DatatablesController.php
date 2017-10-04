<?php

namespace App\Http\Controllers;

use App\IP;
use App\Package;
use App\ResourcePool;
use App\Server;
use App\Template;
use App\User;
use App\VM;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DatatablesController extends Controller
{
    public function customer_vms() {
        $query = VM::query();

        $query->where('user_id', '=', app('auth')->id());

        if ($this->request->has('select')) {
            foreach ($this->request->get('select') as $key => $value) {
                $query = VM::where($key, '=', $value);
            }
        }

        return \Datatables::of($query)
            ->editColumn('id', function (VM $vm) {
                return '<a href="'.route('vm', ['vm_id' => $vm->id]).'">#'.str_pad($vm->id, 6, '0', STR_PAD_LEFT).'</a>';
            })
            ->editColumn('bandwidth', function (VM $vm) {
                return view('admin/vm/bandwidth')->with('vm', $vm);
            })
            ->addColumn('virtualization_type', function (VM $vm) {
                return '<img src="https://forum.openvz.org/theme/ovz3/images/header.gif" width="20px">';
                $vm->virtualization()->getIcon();
            })
            ->addColumn('status', function (VM $vm) {
                if ($vm->suspended) {
                    return '<div class="vm-status suspended"></div>';
                }

                if ($vm->online) {
                    return '<div class="vm-status on"></div>';
                }

                return '<div class="vm-status off"></div>';
            })
            ->addColumn('conf', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'"><img src="https://cdn0.iconfinder.com/data/icons/construction-12/64/spanner-20.png"></a>';
            })
            ->addColumn('speed', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'"><img src="https://cdn1.iconfinder.com/data/icons/dual-stroke-part-four/64/bandwidth-power-dashboard-settings-20.png"></a>';
            })
            ->addColumn('delete', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'"><img src="https://cdn1.iconfinder.com/data/icons/basic-ui-elements-color/700/010_trash-2-20.png"></a>';
            })
            ->make(true);
    }


    public function users() {
        return \Datatables::of(User::select([
            'id',
            'email_address',
            'access_level',
            'created_at'
        ]))
            ->editColumn('email_address', function (User $user) {
                return '<a href="'.route('admin.user', ['user_id' => $user->id]).'">'.$user->email_address.'</a>';
            })
            ->addColumn('vms', function (User $user) {
                return VM::where('user_id', '=', $user->id)->count();
            })
            ->editColumn('access_level', function (User $user) {
                return User::$group[$user->access_level];
            })
            ->make(true);
    }

    public function packages() {
        return \Datatables::of(Package::query())
            ->editColumn('name', function (Package $package) {
                return '<a href="'.route('admin.package', ['package_id' => $package->id]).'">'.$package->name.'</a>';
            })
            ->addColumn('vms', function (Package $package) {
                return '<a href="'.route('admin.vms', ['select' => [
                    'package_id' => $package->id
                ]]).'">'.VM::where('package_id', '=', $package->id)->count().'</a>';
            })
            ->addColumn('delete', function (Package $package) {
                return '<a href="'.route('admin.package-delete', ['package_id' => $package->id]).'" onclick="return confirm(\'Are you sure?\')">DELETE</a>';
            })
            ->make(true);
    }

    public function ips() {
        return \Datatables::of(IP::query())
            ->addColumn('vm', function (IP $ip) {
                if ($ip->hasVM()) {
                    return '<a href="'.route('admin.vm', ['vm_id' => $ip->vps_id]).'">'.$ip->vm()->hostname.'</a>';
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('ip_address', function (IP $ip) {
                return '<a href="'.route('admin.ip', ['vm_id' => $ip->id]).'">'.$ip->ip_address.'</a>';
            })
            ->addColumn('delete', function (IP $ip) {
                return '<a href="'.route('admin.ip-delete', ['ip_id' => $ip->id]).'">DELETE</a>';
            })
            ->make(true);
    }

    public function templates() {
        return \Datatables::of(Template::query())
            ->editColumn('name', function (Template $template) {
                return '<a href="'.route('admin.template', ['template_id' => $template->id]).'">'.$template->getName().'</a>';
            })
            ->make(true);
    }

    public function servers() {
        return \Datatables::of(Server::query())
            ->editColumn('name', function (Server $server) {
                return '<a href="'.route('admin.server', ['server_id' => $server->id]).'">'.$server->name.'</a>';
            })
            ->addColumn('vms', function (Server $server) {
                return $server->vmCount();
            })
            ->addColumn('type', function (Server $server) {
                return '<img src="https://forum.openvz.org/theme/ovz3/images/header.gif" width="20px">';
            })
            ->make(true);
    }

    public function vms($user_id = null) {
        $query = VM::query();

        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }

        if (is_array($this->request->input('select'))) {
            foreach ($this->request->input('select') as $key => $val) {
                $query->where($key, '=', $val);
            }
        }

        if ($this->request->has('select')) {
            foreach ($this->request->get('select') as $key => $value) {
                $query = VM::where($key, '=', $value);
            }
        }

        return \Datatables::of($query)
            ->editColumn('id', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'">#'.str_pad($vm->id, 6, '0', STR_PAD_LEFT).'</a>';
            })
            ->editColumn('server_id', function (VM $vm) {
                if ($vm->server_id) {
                    return '<a href="' . route('admin.server', ['server_id' => $vm->server_id]) . '">' . $vm->server()->name . '</a>';
                }
            })
            ->editColumn('bandwidth', function (VM $vm) {
                return view('admin/vm/bandwidth')->with('vm', $vm);
            })
            ->addColumn('virtualization_type', function (VM $vm) {
                return '<img src="https://forum.openvz.org/theme/ovz3/images/header.gif" width="20px">';
                $vm->virtualization()->getIcon();
            })
            ->addColumn('status', function (VM $vm) {
                if ($vm->suspended) {
                    return '<div class="vm-status suspended"></div>';
                }

                if ($vm->online) {
                    return '<div class="vm-status on"></div>';
                }

                return '<div class="vm-status off"></div>';
            })
            ->edit_column('user_id', function (VM $vm) {
                return '<a href="'.route('admin.user', ['user_id' => $vm->user_id]).'">'.$vm->user()->email_address.'</a>';
            })
            ->addColumn('conf', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'"><img src="https://cdn0.iconfinder.com/data/icons/construction-12/64/spanner-20.png"></a>';
            })
            ->addColumn('speed', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'"><img src="https://cdn1.iconfinder.com/data/icons/dual-stroke-part-four/64/bandwidth-power-dashboard-settings-20.png"></a>';
            })
            ->addColumn('delete', function (VM $vm) {
                return '<a href="'.route('admin.vm', ['vm_id' => $vm->id]).'"><img src="https://cdn1.iconfinder.com/data/icons/basic-ui-elements-color/700/010_trash-2-20.png"></a>';
            })
            ->make(true);
    }

    public function resource_pools() {
        return \Datatables::of(ResourcePool::select())
            ->editColumn('id', function (ResourcePool $rp) {
                return '<a href="'.route('admin.resourcepool', ['resource_pool_id' => $rp->id]).'">#'.str_pad($rp->id, 6, '0', STR_PAD_LEFT).'</a>';
            })
            ->editColumn('user_id', function (ResourcePool $rp) {
                if ($rp->user_id) {
                    return '<a href="' . route('admin.user', ['user_id' => $rp->user_id]) . '">' . User::find($rp->user_id)->email_address . '</a>';
                } else {
                    return 'Unassigned';
                }
            })
            ->addColumn('disk', function (ResourcePool $rp) {
                return view('admin/resource_pools/disk')->with('resource_pool', $rp);
            })
            ->addColumn('swap', function (ResourcePool $rp) {
                return view('admin/resource_pools/swap')->with('resource_pool', $rp);
            })
            ->addColumn('ram', function (ResourcePool $rp) {
                return view('admin/resource_pools/ram')->with('resource_pool', $rp);
            })
            ->addColumn('ips', function (ResourcePool $rp) {
                return $rp->getAvailableIPCount().' available, '.$rp->getAllocatedIPCount().' allocated';
            })
            ->make(true);
    }
}
