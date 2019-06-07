<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;
use Auth;

class ProfileController extends Controller
{
    public function viewProfile()
    {
    	return view('profile.index');
    }

    public function editProfile()
    {
    	return view('profile.edit');
    }

    public function updateProfile(Request $request, $id)
    {
    	$input = $request->input();
    	$validator =  Validator::make($input,[
    		'name' => 'required|string|max:30',
    		'mobile_number' => 'required|numeric',
    		'location' => 'nullable|string|max:50'
    	]);

    	if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       	}

       	$user = User::findOrFail($id);
       	$user->fill($input);
       	$user->save();

       	flash()->success("Profile detail updated successfully.");
       	return redirect()->route('profile');
    }

    public function changePassword()
    {
    	return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
    	$input = $request->input();
    	$validator = Validator::make($input, [
    		'current_password' => 'required|min:6',
    		'new_password' => 'required|confirmed|min:6',
    	]);

    	if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       	}

        if (Hash::check($input['current_password'], Auth::user()->password)) {
            $user = User::findOrFail(Auth::id());
            $user->password = Hash::make($input['new_password']);
            $user->save();
            flash()->success("Password updated successfully");
        } else {
            flash()->error("Current Password is not correct");
        }
        return redirect()->back();
    }
}
