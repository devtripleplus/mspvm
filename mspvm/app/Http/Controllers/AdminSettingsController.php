<?php namespace App\Http\Controllers;


use App\Http\Requests\Settings\EmailSettingsRequest;
use App\Http\Requests\Settings\GeneralSettingsRequest;
use App\Http\Requests\Settings\MaintenanceSettingsRequest;
use App\Http\Requests\Settings\SecuritySettingsRequest;
use App\Http\Requests\Settings\NetworkSettingsRequest;
use App\Option;
use \Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

Class AdminSettingsController extends Controller  {
   public function general() {
        return view('admin/settings/general')
            ->with('title', 'General Settings');
   }

    public function doUpdateGeneral(GeneralSettingsRequest $request) {
        if(Request::hasfile('logo')){
            $fileforstore = Request::file('logo');
            $logo = $fileforstore->getClientOriginalName();
            $path = public_path().'/images/';
            $fileforstore->move($path, $logo);
        }

        
        // Title
        Option::updateOrCreate(
            [
                'setting_name' => 'title'
            ],
            [
                'setting_value' => $request->input('title')
            ]
        );

        // Email
        Option::updateOrCreate(
            [
                'setting_name' => 'email'
            ],
            [
                'setting_value' => $request->input('email')
            ]
        );

        // Logo
        Option::updateOrCreate(
            [
                'setting_name' => 'logo'
            ],
            [
                'setting_value' => $logo
            ]
        );

        // Timezone
        Option::updateOrCreate(
            [
                'setting_name' => 'timezone'
            ],
            [
                'setting_value' => $request->input('timezone')
            ]
        );


        // support_url
        Option::updateOrCreate(
            [
                'setting_name' => 'supporturl'
            ],
            [
                'setting_value' => $request->input('supporturl')
            ]
        );

        return redirect()->back()->withMessage('The settings have been updated!');
    }

    public function security() {
        return view('admin/settings/security')
            ->with('title', 'Security Settings');
    }
    // to retrive the logo from settings table
    public static function getLogo() {
        return DB::table('settings')->select('setting_value')->where('setting_name','logo')->get();
    }


    // to retrive the network_setting from settings table
    public static function getNetworkSettings() {
        $settings = DB::table('settings')->select(['setting_name','setting_value'])->where('setting_group','network_setting')->get();
        $nwksettings = array();
        
        foreach($settings as $value){
            $nwksettings[$value->setting_name] = $value->setting_value;
        }
        return $nwksettings;
    }

    // to retrive the site_setting from settings table
    public static function getGeneralSettings() {
        return DB::table('settings')->select('setting_value')->where('setting_name','logo')->get();
    }

    public function bandwidthsuspension($status)
    {
        $setting = DB::table('settings')->where('setting_name','bandwidthsuspension')->get();
        if(!empty($setting)){
            DB::table('settings')->where('id',$setting[0]->id)->update(['setting_value' => $status]);
        }
        else{
            $id = DB::table('settings')->insert(['setting_name' => 'bandwidthsuspension', 'setting_value' => $status, 'setting_group' => 'network_setting']);
        }

        return redirect()->back()->withMessage('The settings have been updated!');
    }

    public function doUpdateSecurity(SecuritySettingsRequest $request) {
        // Client hijack check
        Option::updateOrCreate(
            [
                'name' => 'client-hijack-check'
            ],
            [
                'value' => $request->input('client-hijack-check')
            ]
        );

        // Admin hijack check
        Option::updateOrCreate(
            [
                'name' => 'admin-hijack-check'
            ],
            [
                'value' => $request->input('admin-hijack-check')
            ]
        );

        // Hijack check type
        Option::updateOrCreate(
            [
                'name' => 'hijack-check-type'
            ],
            [
                'value' => $request->input('hijack-check-type')
            ]
        );

        return redirect()->back()->withMessage('The settings have been updated!');
    }

    public function network() {
        return view('admin/settings/network')
            ->with('title', 'Network Settings');
   }

   public function doUpdateNetwork(NetworkSettingsRequest $request) {

        if($request->input('networkadapter')){
            $setting = DB::table('settings')->where('setting_name','networkadapter')->get();
            if(!empty($setting)){
                DB::table('settings')->where('id',$setting[0]->id)->update(['setting_value' => $request->input('networkadapter')]);
            }
            else{
                $id = DB::table('settings')->insert(['setting_name' => 'networkadapter', 'setting_value' => $request->input('networkadapter'), 'setting_group' => 'network_setting']);
            }
        }

        if($request->input('maxbandwidth')){
            $setting = DB::table('settings')->where('setting_name','maxbandwidth')->get();
            //print_r($setting[0]->id);
            if(!empty($setting)){
                DB::table('settings')->where('id',$setting[0]->id)->update(['setting_value' => $request->input('maxbandwidth')]);
            }
            else{
                $id = DB::table('settings')->insert(['setting_name' => 'maxbandwidth', 'setting_value' => $request->input('maxbandwidth'), 'setting_group' => 'network_setting']);
            }
        }

        if($request->input('cap')){
            $setting = DB::table('settings')->where('setting_name','speed_capping')->get();
            //print_r($setting[0]->id);
            if(!empty($setting)){
                DB::table('settings')->where('id',$setting[0]->id)->update(['setting_value' => $request->input('cap')]);
            }
            else{
                $id = DB::table('settings')->insert(['setting_name' => 'speed_capping', 'setting_value' => $request->input('cap'), 'setting_group' => 'network_setting']);
            }
        }
        if($request->input('limit')){
            $setting = DB::table('settings')->where('setting_name','limit')->get();
            //print_r($setting[0]->id);
            if(!empty($setting)){
                DB::table('settings')->where('id',$setting[0]->id)->update(['setting_value' => $request->input('maxbandwidth')]);
            }
            else{
                $id = DB::table('settings')->insert(['setting_name' => 'limit', 'setting_value' => $request->input('limit'), 'setting_group' => 'network_setting']);
            }
        }
        return redirect()->back()->withMessage('The settings have been updated!');
    }

    public function maintenance() {
        return view('admin/settings/maintenance')
            ->with('title', 'Maintenance Settings');
    }

    public function doUpdateMaintenance(MaintenanceSettingsRequest $request) {
        // Backup Frequency
        Option::updateOrCreate(
            [
                'name' => 'database-backup-frequency'
            ],
            [
                'value' => $request->input('database-backup-frequency')
            ]
        );

        // Backup Frequency
        Option::updateOrCreate(
            [
                'name' => 'database-backup-limit'
            ],
            [
                'value' => $request->input('database-backup-limit')
            ]
        );

        // Log Prune Frequency
        Option::updateOrCreate(
            [
                'name' => 'log-prune-interval'
            ],
            [
                'value' => $request->input('log-prune-interval')
            ]
        );

        return redirect()->back()->withMessage('The settings have been updated!');
    }

    public function email() {
        return view('admin/settings/mail')
            ->with('title', 'Mail Settings');
    }

    public function doUpdateEmail(EmailSettingsRequest $request) {
        foreach ([
            'method',
            'port',
            'host',
            'security',
            'username',
            'password'
                 ] as $key) {
            // Title
            Option::updateOrCreate(
                [
                    'name' => 'mail.'.$key
                ],
                [
                    'value' => $request->input($key)
                ]
            );
        }

        return redirect()->back()->withMessage('The settings have been updated!');
    }
}