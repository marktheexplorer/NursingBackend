<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Validator;
use Auth;
use Session;
use Cache;

class ProfileController extends Controller{
    public function viewProfile(){
    	return view('profile.index');
    }

    public function editProfile(){
    	return view('profile.edit');
    }

    public function updateProfile(Request $request, $id){
    	$input = $request->input();
    	$validator =  Validator::make($input,[
    		'name' => 'required|string|max:30',
    		'mobile_number' => 'required|numeric',
    		'location' => 'nullable|string|max:100',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    	]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
       	}

        $upload_image = '';
        $profile_image = $request->file('profile_image');
        if(!empty($profile_image)){
            $imageName = time().'.'.$profile_image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile_images');
            $imagePath = $destinationPath. "/".  $imageName;
            $profile_image->move($destinationPath, $imageName);
            $input['profile_image'] =  "/uploads/profile_images/".$imageName;
        }

       	$user = User::findOrFail($id);
       	$user->fill($input);
       	$user->save();

       	flash()->success("Profile detail updated successfully.");
       	return redirect()->route('profile');
    }

    public function changePassword(){
    	return view('profile.change-password');
    }

    public function updatePassword(Request $request){
    	$input = $request->input();
    	$validator = Validator::make($input,
            [
        		'current_password' => 'required|min:6',
        		'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|min:6|same:new_password',
        	],
            ['new_password_confirmation.same' => 'New password and confirm password does not match.']
        );

        //show custome name of field in validation errors
        $attributeNames = array(
           'new_password_confirmation' => 'Confirm Password',
        );
        $validator->setAttributeNames($attributeNames);


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

    public function signout(Request $request){
        Auth::logout(); // logout user
        Session::flush();
        Redirect::back();
        Cache::flush();
        return redirect(\URL::previous());
    }
}
