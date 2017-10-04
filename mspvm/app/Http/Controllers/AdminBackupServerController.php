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
use Illuminate\Http\Request;

Class AdminBackupServerController extends Controller  {
    public function servers() {
        return view('admin/backup_servers')
            ->with('backup_servers', BackupServer::all())
            ->with('title', 'Backup Servers');
    }

    public function create() {
        return view('admin/backup_server_create')
            ->with('title', 'New Backup Server');
    }

    public function doCreate(BackupServerRequest $request) {
        $server = BackupServer::create([
           'host' => $request->input('host'),
            'user' => $request->input('user'),
            'password' => $request->input('password'),
            'port' => $request->input('port'),
            'directory' => $request->input('directory')
        ]);

        return redirect()->route('admin.backup-servers')->withMessage('Success!');
    }
}