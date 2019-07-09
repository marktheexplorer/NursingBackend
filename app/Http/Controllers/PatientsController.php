<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Diagnose;
use Validator;
use App\PatientProfile;
use Carbon\Carbon;
use Image;

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
        $diagnosis = Diagnose::get();
    	return view('patients.edit' , compact('user','diagnosis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->input();
        $validator = validator::make($input,[
            'name' => 'required|string|max:60',
            'email' => 'required|string|max:60',
            'mobile_number' => 'required|numeric',
            'dob' => 'required',
            'gender' => 'required',
            'range' => 'required|numeric',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }   

        if($request->has('profile_image') && ($request->file('profile_image') != null)) {
                $image = $request->file('profile_image');
                $user = User::findOrFail($id);
                $input['profile_image'] = time().'.'.$image->getClientOriginalExtension();   
                $user->profile_image = $input['profile_image'];

                $destinationPath = config('image.user_image_path');
                $img = Image::make($image->getRealPath());
                $image->move($destinationPath, $input['profile_image']);
            }
                $user = User::findOrFail($id);
                $user->name = $input['name'];
                $user->email = $input['email'];
                $user->mobile_number = $input['mobile_number'];
                $user->city = $input['city'];
                $user->state = $input['state'];
                $user->country = $input['country'];
                $user->save();

                $userProfile = PatientProfile::where('user_id',$id)->first();
                if($userProfile){
                    $userProfile['dob'] = Carbon::parse($input['dob'])->format('Y-m-d H:i:s');
                    $userProfile['gender'] = $input['gender'];
                    $userProfile['range'] = $input['range'];
                    $userProfile['pin_code'] = $input['pin_code'];
                    $userProfile['diagnose_id'] = $input['diagnose_id'];
                    $userProfile['availability'] = $input['availability'];
                    $userProfile->save();
                }else{
                    $profile['dob'] = Carbon::parse($input['dob'])->format('Y-m-d H:i:s');
                    $profile['user_id'] = $patient->id;
                    $profile['gender'] = $input['gender'];
                    $profile['range'] = $input['range'];
                    $profile['pin_code'] = $input['pin_code'];
                    $profile['diagnose_id'] = $input['diagnose_id'];
                    $profile['availability'] = $input['availability'];
                    $profile = PatientProfile::create($profile);
                }

                flash()->success('Patient updated successfully');
                return redirect()->route('patients.index');
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
    
    public function create(){
        $diagnosis = Diagnose::get();
        return view('patients.create', compact('diagnosis'));
    }

    public function store(Request $request){
        $input = $request->input();
        $validator = validator::make($input,[
            'name' => 'required|string|max:60',
            'email' => 'required|string|max:60|unique:users',
            'mobile_number' => 'required|numeric|unique:users',
            'dob' => 'required',
            'gender' => 'required',
            'range' => 'required|numeric',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

         if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
         }

         if($request->has('profile_image') && ($request->file('profile_image') != null)) {
                $image = $request->file('profile_image');
                $input['profile_image'] = time().'.'.$image->getClientOriginalExtension();   

                $destinationPath = config('image.user_image_path');
                $img = Image::make($image->getRealPath());
                $image->move($destinationPath, $input['profile_image']);
            }

            $input['name'] = $input['name'];
            $input['role_id'] = 3;
            $input['email'] = $input['email'];
            $input['mobile_number'] = $input['mobile_number'];
            $input['city'] = $input['city'];
            $input['state'] = $input['state'];
            $input['country'] = $input['country'];
            $input['password'] = Hash::make('123456');
            $patient = User::create($input);

            $profile['dob'] = Carbon::parse($input['dob'])->format('Y-m-d H:i:s');
            $profile['user_id'] = $patient->id;
            $profile['gender'] = $input['gender'];
            $profile['range'] = $input['range'];
            $profile['pin_code'] = $input['pin_code'];
            $profile['diagnose_id'] = $input['diagnose_id'];
            $profile['availability'] = $input['availability'];
            $profile = PatientProfile::create($profile);

            flash()->success('New Patient added successfully');
            return redirect()->route('patients.index');
    }

    public function show($id){
        $user = User::findOrFail($id);
        if($user->patient){
            $diagnosis = Diagnose::where('id',$user->patient->diagnose_id)->first();
        }else{
            $diagnosis = '';
        }
        return view('patients.view', compact('user','diagnosis'));
    }
}
