<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller{
    public function home(){
    	if (Auth::check()) {
    		return redirect()->route('dashboard');
    	} else {
    		return view('auth.login');
    	}
    }

    public function web_logout(Request $request){
        die('this is smy work');
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route( 'admin.login' ));
    }
}
