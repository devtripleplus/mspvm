<?php namespace App\Providers;

use App\Mail\MailTransportManager;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider {

    protected function registerSwiftTransport(){
        $this->app['swift.transport'] = $this->app->share(function($app)
        {
            return new MailTransportManager($app);
        });
    }
}