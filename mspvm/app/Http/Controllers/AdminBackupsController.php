<?php namespace App\Http\Controllers;

use App\Backup;
use App\BackupServer;
use App\Http\Requests\BackupServerRequest;
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
use Carbon\Carbon;
use Illuminate\Http\Request;

Class AdminBackupsController extends Controller  {
    public function backups() {
        $backups = [];

        $scan = scandir(storage_path('backups/'));

        usort($scan, function ($a, $b) {
            return filemtime(storage_path('backups/'.$a)) >=  filemtime(storage_path('backups/'.$b));
        });

        foreach ($scan as $file) {
            if ($file != '..' && $file != '.' && is_file(storage_path('backups/'.$file))) {
              $backups[] = [
                'name' => $file,
                  'date' => Carbon::createFromTimestamp(filemtime(storage_path('backups/'.$file)))
              ];
            }
        }
        return view('admin/backups')
            ->with('backups', $backups)
            ->with('title', 'Backups');
    }

    public function doDeleteBackup($file) {
        if ($file == '' || strpos($file, '/') !== false) {
            return redirect()->back();
        }

        if (file_exists(storage_path('backups/'.$file))) {
            unlink(storage_path('backups/'.$file));
        }

        return redirect()->back()->withMessage('The backup has been deleted!');
    }
}