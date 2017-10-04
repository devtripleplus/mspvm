<?php namespace App\Http\Controllers;

use App\Server;

Class ClientHomeController extends Controller  {
    public function home() {
        return view('client_home')
            ->with('servers', $this->user->servers()->get())
            ->with('title', 'Home');
    }
}