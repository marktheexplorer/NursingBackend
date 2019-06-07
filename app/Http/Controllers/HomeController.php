<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    public function home()
    {
    	if (Auth::check()) {
    		return redirect()->route('dashboard');
    	} else {
    		return view('auth.login');
    	}

    }
}
