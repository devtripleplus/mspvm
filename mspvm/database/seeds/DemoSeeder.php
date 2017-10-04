<?php

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'username' => 'root',
            'password' => Hash::make('password'),
            'access_level' => 3
        ]);

        \App\User::create([
            'username' => 'client',
            'password' => Hash::make('password'),
            'access_level' => 1
        ]);

        \App\Server::create([
            'name' => 'LOCALHOST',
            'ip' => 'localhost',
            'user' => 'root',
            'key' => 'mspserver123*'
        ]);

        \App\Package::create([
            'name' => 'TEST Package',
            'ram' => 1000,
            'swap' => 2000,
            'disk' => 2000,
            'cpu_units' => 300,
            'cpu_limit' => 40,
            'bandwith_limit' => 100,
            'inode_limit' => 200,
            'burst' => 2000,
            'cpus' => 4,
            'network_speed' => 100
        ]);

        static::seedDemoTemplate();
    }

    private static function seedDemoTemplate() {
        $name = 'OpenVZ';
        $file = 'http://download.openvz.org/contrib/template/precreated/ubuntu-6.06-i386-minimal.tar.gz';

        $filename = \Illuminate\Support\Str::slug($name).'_x64.tar.gz';

        $error = exec('wget -O /var/mspvm/templates/'.strtolower(\App\Server::$virtualization[1]).'/'.$filename.' '.$file);

        if ($error) {
            return redirect()->back()->withErrors([
                'wget failed with error '.$error
            ]);
        }

        $template = \App\Template::create(array_merge([
            'name' => $name,
            'type' => 1,
            'architecture' => 'x64',
            'description' => 'Test'
        ], [
            'path' => $filename,
            'size' => 0,
            'disabled' => 0
        ]));
    }
}
