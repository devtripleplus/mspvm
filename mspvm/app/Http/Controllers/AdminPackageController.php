<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\PackageRequest;
use App\Package;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Class AdminPackageController extends Controller  {
    public function all() {
        return view('admin/packages')
            ->with('packages', Package::all())
            ->with('title', 'Packages');
    }

    public function package($package_id) {
        $package = Package::find($package_id);

        if (!$package) {
            throw new NotFoundHttpException;
        }

        return view('admin/package')
            ->with('package', $package)
            ->with('title', $package->name);
    }

    public function doDelete($package_id) {
        $package = Package::find($package_id);

        if (!$package) {
            throw new NotFoundHttpException;
        }

        $package->delete();

        return redirect()->route('admin.packages')->withMessage('The package has been deleted!');
    }

    public function doUpdate($package_id, PackageRequest $request) {
        $this->validate($request, [
            'name' => 'required|unique:packages,name,'.$package_id.'|max:50'
        ]);

        $package = Package::find($package_id);

        if (!$package) {
            throw new NotFoundHttpException;
        }

        $package->update($request->only(
            'name',
            'ram',
            'swap',
            'disk',
            'cpu_units',
            'cpu_limit',
            'bandwith_limit',
            'inode_limit',
            'burst',
            'cpus',
            'network_speed'
        ));

        return redirect()->back()->withMessage('Package updated!');
    }

    public function create() {
        return view('admin/package_create')
            ->with('title', 'New Package');
    }

    public function doCreate(PackageRequest $request) {
        $this->validate($request, [
            'name' => 'required|unique:packages,name|max:50'
        ]);

        $package = Package::create($request->only(
            'name',
            'ram',
            'swap',
            'disk',
            'cpu_units',
            'cpu_limit',
            'bandwith_limit',
            'inode_limit',
            'burst',
            'cpus',
            'network_speed'
        ));

        return redirect()->route('admin.package', ['package_id' => $package->id])->withMessage('Package created!');
    }
}