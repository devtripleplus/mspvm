<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/**
 * @TODO Move to helpers
 */
function pingSSH($domain){
    if ($domain == "localhost") {
        return true;
    }

    try {
        $starttime = microtime(true);
        $file = fsockopen($domain, 22, $errno, $errstr, 2);
        $stoptime = microtime(true);
        $status = 0;

        if (!$file) $status = -1;  // Site is down
        else {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status;
    } catch (\ErrorException $e) {
        return false;
    }
}

function settings($name) {
    return config('settings.'.$name);
}

function get_friendly_arc_name($type) {
    switch ($type) {
        case 1:
            return 'x86';
        case 2:
            return 'x64';
    }
}

function get_friendly_backup_method_name($name) {
    $method = array_first(app('backup'), function ($i, $m) use($name) {
        return get_class($m) == $name;
    });

    if ($method) {
        return $method->getName();
    }

    return $name;
}


/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
