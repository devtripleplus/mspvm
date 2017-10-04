<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BackupMethodServiceProvider extends ServiceProvider
{
    public function register() {
        // register config
        $this->app['config']['backup'] = require config_path('backup.php');

        $this->app->singleton('backup', function () {
            return array_map(function ($method) {
                if (!is_object($method)) {
                    return new $method;
                }

                return $method;
            }, $this->app['config']['backup']);
        });
    }
}
