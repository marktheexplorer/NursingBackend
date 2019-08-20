<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Diagnose;
use App\Service_requests;
use Validator;
use App\PatientProfile;
use Carbon\Carbon;
use Image;
use DB;

use Illuminate\Http\Request;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $patients = User::where('role_id','3')->orderBy('created_at', 'DESC')->get();
        return view('patients.index', compact('patients'));
    }

    public function activePatients(){
        $patients = User::where('role_id','3')->where('is_blocked',0)->get();
        return view('patients.index', compact('patients'));
    }

    public function inactivePatients(){
        $patients = User::where('role_id','3')->where('is_blocked',1)->get();
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
            'mobile_number' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'range' => 'required|numeric',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'addtional_info' => 'nullable|max:2000',
            'pets' => 'required',
            'pets_description' => 'nullable|max:2000'
        ]);
        if($input['pets'] == 'yes'){
            $this->validate($request, [
              'pets_description' => 'required|max:2000'
            ]);
        }

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
                $user->dob = date("Y-m-d", strtotime($input['dob']));
                $user->gender = $input['gender'];
                $user->save();

                $userProfile = PatientProfile::where('user_id',$id)->first();
                if($userProfile){
                    $userProfile['range'] = $input['range'];
                    $userProfile['pin_code'] = $input['pin_code'];
                    $userProfile['diagnose_id'] = $input['diagnose_id'];
                    $userProfile['availability'] = $input['availability'];
                    $userProfile['pets'] = $input['pets'] == 'yes'? 1 : 0;
                    $userProfile['pets_description'] = $input['pets'] == 'yes'? $input['pets_description'] : '';
                    $userProfile['additional_info'] = $input['additional_info'];
                    $userProfile->save();
                }else{
                    $profile['user_id'] = $user->id;
                    $profile['range'] = $input['range'];
                    $profile['pin_code'] = $input['pin_code'];
                    $profile['diagnose_id'] = $input['diagnose_id'];
                    $profile['availability'] = $input['availability'];
                    $profile['pets'] = $input['pets'] == 'yes'? 1 : 0;
                    $profile['pets_description'] = $input['pets_description'];
                    $profile['additional_info'] = $input['additional_info'];
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
            'mobile_number' => 'required|unique:users',
            'dob' => 'required',
            'gender' => 'required',
            'range' => 'required|numeric',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'addtional_info' => 'nullable|max:2000',
            'pets' => 'required',
            'pets_description' => 'max:2000'
        ]);
        if(!empty($input['pets']) && $input['pets'] == 'yes'){
            $this->validate($request, [
              'pets_description' => 'required|max:2000'
            ]);
        }

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
            $input['type'] = 'patient';
            $input['password'] = Hash::make('123456');
            $input['dob'] = date("Y-m-d", strtotime($input['dob']));
            $input['gender'] = $input['gender'];
            $patient = User::create($input);

            $profile['user_id'] = $patient->id;
            $profile['range'] = $input['range'];
            $profile['pin_code'] = $input['pin_code'];
            $profile['diagnose_id'] = $input['diagnose_id'];
            $profile['availability'] = $input['availability'];
            $profile['pets'] = $input['pets'] == 'yes'? 1 : 0;
            $profile['pets_description'] = $input['pets'] == 'yes'? $input['pets_description'] : '';
            $profile['additional_info'] = $input['additional_info'];
            $profile = PatientProfile::create($profile);

            flash()->success('New Patient added successfully');
            return redirect()->route('patients.index');
    }

    public function show($id){
        $user = User::findOrFail($id);
        $services = DB::table('service_requests')
                    ->join('services' ,'services.id','service_requests.service')
                    ->join('service_requests_attributes AS ser_att' , 'ser_att.service_request_id' , 'service_requests.id')
                    ->join('users' , 'users.id' , 'ser_att.value')
                    ->select('service_requests.*' ,'services.title' , 'users.name' ,'ser_att.type')
                    ->where('user_id',$user->id)
                    ->where('ser_att.type','final_caregiver')->get();

        if($user->patient){
            $diagnosis = Diagnose::where('id',$user->patient->diagnose_id)->first();
        }else{
            $diagnosis = '';
        }
        return view('patients.view', compact('user','diagnosis','services'));
    }

    public function locationfromzip(Request $request){
        $pincode = $request->input('pin_code');
        $search_pin = DB::select( DB::raw("SELECT * FROM `us_location` where zip = '".$pincode."'")); 

        $response = array();
        $response['error'] = false;
        if(empty($search_pin)){
            $response['error'] = true;
            $response['msg'] = 'Invalid zipcode';
        }else{
            $response['city'] = $search_pin[0]->city;
            $response['state'] = $search_pin[0]->state_code;
        }
        echo json_encode($response, true);
    }
}
