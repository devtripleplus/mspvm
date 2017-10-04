<?php namespace App\Http\Controllers;


use App\Server;
use App\VM;

Class AdminHomeController extends Controller  {
    public function home() {
        return view('admin/home')
            ->with('servers', Server::all())
            ->with('title', 'Home');
    }
}