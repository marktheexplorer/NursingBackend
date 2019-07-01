<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

class UserController extends Controller{
    public function index(){
        $users = User::where('id','>', '1')->where('is_blocked', '=', 0)->get();
        return view('users.index', compact('users'));
    }

    public function blocklist(){
        $users = User::where('id','>', '1')->where('is_blocked', '=', 1)->get();
        return view('users.index', compact('users'));
    }   

    public function create(){
        return view('users.create');
    }

    public function store(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,[
            'name' => 'required|string|max:30',
            'email' => 'email|required|unique:users',
            'mobile_number' => 'required|numeric|unique:users',
            'location' => 'nullable|string|max:50',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->role_id = 2;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->mobile_number = $input['mobile_number'];
        $user->location = $input['location'];
        $user->password = Hash::make($input['password']);
        $user->save();

        flash()->success('New user added successfully');
        return redirect()->route('users.index');
    }

    public function show($id){
        $user = User::findOrFail($id);
        return view('users.view', compact('user'));
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id){
        $input = $request->input();
        $validator =  Validator::make($input,[
            'name' => 'required|string|max:30',
            'mobile_number' => 'required|numeric',
            'location' => 'nullable|string|max:50',
            'password' => 'nullable|min:6',
            'email' => 'email|required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = User::findOrFail($id);
        $user->role_id = 2;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->mobile_number = $input['mobile_number'];
        $user->location = $input['location'];
        if ($input['password'] != null) {
            $user->password = Hash::make($input['password']);
        }
        $user->save();

        flash()->success("User detail updated successfully.");
        return redirect()->route('users.index');
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        if ($user->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'User deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'User can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }

    public function block($id){
        $user = User::find($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();
       
        if ($user->is_blocked)
            flash()->success("User blocked successfully."); 
        else 
            flash()->success("User Unblocked successfully."); 

        return redirect()->route('users.index');  
    }
}