<?php

namespace App\Console;

use App\Backup;
use App\Notification;
use App\Server;
use App\Template;
use App\TemplateDeployment;
use App\VM;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $this->scheduleBackups($schedule);

        $this->scheduleLocalDatabaseBackups($schedule);

        return;

        $schedule->call(function () {
            set_time_limit(0);

            $servers = Server::all();

            foreach (Template::all() as $template) {
                $deployed = TemplateDeployment::where('template_id', '=', $template->id)->get();

                if (count($deployed) != count($servers)) {
                    foreach ($servers as $server) {
                        $found = count(array_filter($deployed->toArray(), function ($deployed) use($server) {
                                return $deployed['id'] == $server->id;
                            })) != 0;

                        if (!$found) {
                            $sem_id = sem_get($template->id*10000+$server->id);

                            if (!sem_acquire($sem_id, true)) {
                                continue; // Another deployment is already in process
                            }

                            $error = '';

                            // Deploy
                            try {
                                if ($server->virtualization()->deploy_template($template, $error)) {
                                    $dep = TemplateDeployment::create([
                                        'server_id' => $server->id,
                                        'template_id' => $template->id,
                                        'status' => 1
                                    ]);
                                }
                            } catch (\ErrorException $e) {
                                // prevent exceptions from blocking deployment on other servers
                            }

                            sem_release($sem_id);
                        }
                    }
                }
            }
        })->everyFiveMinutes();

        $this->scheduleAlerts($schedule);
    }

    private function scheduleLocalDatabaseBackups(Schedule $schedule) {
        $event = $schedule->call(function () {
            // enforce limit
            if (settings('database-backup-limit') > 0) {
                $scan = scandir(storage_path('backups/'));

                usort($scan, function ($a, $b) {
                    return filemtime(storage_path('backups/' . $a)) < filemtime(storage_path('backups/' . $b));
                });

                $i = 0;

                while (count($scan) - 2 >= settings('database-backup-limit') && $i < count($scan)) {
                    if ($scan[$i]['name'] == '.' || $scan[$i]['name'] == '..') {
                        $i++;
                        continue;
                    }

                    if (is_file(storage_path('backups/'.$scan[$i]['name']))) {
                        unlink(storage_path('backups/'.$scan[$i]['name']));
                        unset($scan[$i]);
                    }
                }

                exec('mysqldump -u ' . config('database.connections.mysql.username') . ' -p ' . stripslashes(config('database.connections.mysql.password')) . ' ' . config('database.connections.mysql.database') . ' > ' . storage_path('backups/' . Carbon::now()->toDateTimeString() . '.sql'));
            }
        });

        return;

        switch (settings('database-backup-frequency')) {
            case 4:
                $event->weekly();
                break;
            case 3:
                $event->daily();
                break;
            case 2:
                $event->twiceDaily();
                break;
            case 1:
                $event->hourly();
                break;
        }
    }

    private function convertThresholdToValue($type, $value) {
        switch ($type) {
            case 'usedcpu':
            case 'usedram':
                return $value / pow(1024, 2);
                break;
            default:
                return $value;
        }
    }

    private function scheduleAlerts(Schedule $schedule) {
        // Alerts
        $schedule->call(function () {
            $virt_container = $server_stats = $vm_stats = [];

            // Node notifications
            foreach (Notification::where('type', 1)->get() as $alert) {
                foreach (Server::all() as $server) {
                    if (!isset($virt_container[$server->id])) {
                        $virt_container[$server->id] = $server->virtualization();
                    }

                    if (!isset($server_stats[$server->id])) {
                        $data = $virt_container[$server->id]->info();
                        $server_stats[$server->id] = $virt_container[$server->id]->getNodeStats($data);
                    }

                    if (method_exists($server_stats[$server->id], 'get' . $alert->target) && ($value = call_user_func([$server_stats[$server->id], 'get' . $alert->target])) >= $this->convertThresholdToValue($alert->target, $alert->target_treshold)) {
                        Mail::send('email/alert/server', [
                            'email' => $alert->email,
                            'server' => $server,
                            'type' => $alert->target,
                            'threshold' => $alert->target_treshold,
                            'value' => $value
                        ], function ($m) use($alert) {
                            $m->to($alert->email)->subject('Alert: '.$alert->getRepresentation().' threshold exceeded');
                        });
                    }
                }
            }

            // VM notifications
            foreach (Notification::where('type', 2)->get() as $alert) {
                $vm = VM::find($alert->target_id);

                if (!isset($virt_container[$vm->server_id])) {
                    $virt_container[$vm->server_id] = $vm->virtualization();
                }

                if (!isset($vm_stats[$vm->id])) {
                    $data = $virt_container[$vm->server_id]->stats($vm);
                    $vm_stats[$vm->id] = $virt_container[$vm->server_id]->getVMStats($data);
                }

                if (method_exists($vm_stats[$vm->id], 'get'.$alert->target) && ($value = call_user_func([$vm_stats[$vm->id], 'get'.$alert->target])) >= $alert->target_treshold) {
                    Mail::send('email/alert/vm', [
                        'email' => $alert->email,
                        'vm' => $vm,
                        'type' => $alert->target,
                        'threshold' => $alert->target_treshold,
                        'value' => $value
                    ], function ($m) use($alert, $vm) {
                        $m->to($alert->email)->subject('Alert: '.$alert->getRepresentation().' threshold exceeded on VM #'.$vm->id);
                    });
                }
            }
        })->everyMinute();
    }

    private function scheduleBackups(Schedule $schedule) {
        $schedule->call(function () {
            Backup::create([
               'vps_id' => 4,
                'date' => time(),
                'method' => Backup\FTPServerBackup::class,
                'checksum' => 'hah'
            ]);
        })->everyMinute();
    }
}
