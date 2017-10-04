<?php namespace App\Mail;

use Illuminate\Mail\TransportManager;

class MailTransportManager extends TransportManager {

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->app['config']['mail'] = [
            'driver'        => settings('mail.method'),
            'host'          => settings('mail.host'),
            'port'          => settings('mail.port'),
            'from'          => [
                'address'   => settings('email'),
                'name'      => settings('title')
            ],
            'encryption'    => settings('security'),
            'username'      => settings('mail.user'),
            'password'      => settings('mail.password'),
            'sendmail'      => '/usr/sbin/sendmail -bs',
            'pretend'       => false
        ];

    }
}