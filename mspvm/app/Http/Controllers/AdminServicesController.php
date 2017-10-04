<?php namespace App\Http\Controllers;

Class AdminServicesController extends Controller  {
    private $serviceArray = array( 'nginx', 'mysqld', 'network', 'iptables' );

    public function services() {
        return view('admin/services')
            ->with('title', 'Services');
    }  
    

    public function serviceRestart($service) {
        if(!in_array($service, $this->serviceArray))
        {
            return redirect()->back();
        }

        $output = $this->execCommand("sudo /sbin/service $service restart 2>&1");

        return redirect()->back()->with('message', 'The service has been restarted. Debug: '. $output);
    }


    //not sure whether this function is already available
    private function execCommand($command)
    {
        $output = "";
        exec($command, $output);
        return implode(' ', $output);
    }
}
?>