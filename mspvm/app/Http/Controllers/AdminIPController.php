<?php namespace App\Http\Controllers;

use App\Http\Requests\IPRequest;
use App\IP;

Class AdminIPController extends Controller  {
    public function ips() {
        return view('admin/ips')
            ->with('ips', IP::orderBy('vps_id', 'ASC')->get())
            ->with('title', 'IPs');
    }

    public function ip($ip_id) {
        $ip = IP::find($ip_id);

        if (!$ip) {
            return redirect()->route('admin.ips')->withErrors('Invalid IP!');
        }

        return view('admin/ip')
            ->with('ip', $ip)
            ->with('title', $ip->ipaddress);
    }

    public function doDelete($ip_id) {
        $ip = IP::find($ip_id);

        if (!$ip) {
            return redirect()->route('admin.ips')->withErrors('Invalid IP!');
        }

        // Additional checks for IPs assigned to a VM
        if ($ip->hasVM()) {
            if ($ip->vm()->primary_ip == $ip->ip_address) {
                return redirect()->route('admin.ip', ['ip_id' => $ip->id])->withErrors('The IP is the primary ip address of a virtual machine!');
            } else {
                $ip->vm()->virtualization()->removeIP($ip->vm(), $ip);
            }
        }

        $ip->forceDelete();

        return redirect()->route('admin.ips')->withMessage('The IP has been deleted!');
    }

    public function create() {
        return view('admin/ip_create')
            ->with('title', 'New IPs');
    }

    public function doCreate(IPRequest $request) {
        IP::create(array_merge($request->only(
            'address'
        )));

        return redirect()->route('admin.ips')->withMessage('IPs added!');
    }
}