<?php

use Illuminate\Database\Seeder;

class settings_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Option::create([
            'setting_name' => 'title',
            'setting_value' => 'MSPVS',
            'setting_group' => 'site_settings'
        ]);

        \App\Option::create([
            'setting_name' => 'description',
            'setting_value' => 'VPS Management',
            'setting_group' => 'site_settings'
        ]);

        \App\Option::create([
            'setting_name' => 'current_version',
            'setting_value' => '0.beta',
            'setting_group' => 'site_settings'
        ]);
    }
}
