<?php

namespace App\Http\Controllers;
use App\User;

use Illuminate\Http\Request;

class PatientsController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $patients = User::where('role_id','3')->get();
    	return view('patients.index', compact('patients'));
    }

    public function edit($id){
    	$user = User::findOrFail($id);
    	return view('patients.edit' , compact('user'));
    }

    public function block($id){
        $user = User::find($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();
       
        if ($user->is_blocked)
            flash()->success("Patient blocked successfully."); 
        else 
            flash()->success("Patient Unblocked successfully."); 

        return redirect()->route('patients.index');  
    }
    
}
