<?php namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Notification;
use App\VM;

Class AdminNotificationsController extends Controller  {
    public function notifications() {
        return view('admin/alerts')
            ->with('alerts', Notification::all())
            ->with('title', 'Alerts');
    }

    public function create() {
        return view('admin/alert_create')
            ->with('title', 'New Alert');
    }

    public function doCreate(NotificationRequest $request) {
        $data = [
            'type' => $request->input('type'),
            'target_treshold' => $request->input('threshold'),
            'email' => $request->input('email')
        ];

        $data['target'] = $request->input('target_'.$data['type']);

        Notification::create($data);

        return redirect()->back()->withMessage('Success!');
    }

    public function doDelete($alert_id) {
        $alert = Notification::find($alert_id);

        if (!$alert) {
            return redirect()->back()->withErrors([
               'Invalid alert!'
            ]);
        }

        $alert->delete();

        return redirect()->back()->withMessage('The alert has been deleted!');
    }
}