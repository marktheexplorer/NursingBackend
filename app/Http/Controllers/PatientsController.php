<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Diagnose;
use App\Service_requests;
use Validator;
use App\PatientProfile;
use App\Qualification;
use App\Us_location;
use Carbon\Carbon;
use Image;
use DB;

use Illuminate\Http\Request;

use App\Exports\PatientExport;
use Maatwebsite\Excel\Facades\Excel;

class PatientsController extends Controller{
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
        $qualifications = Qualification::orderBy('name', 'asc')->get();
        $selected_disciplines = explode(',', $user->patient? $user->patient->disciplines: '');
        $city_state = DB::table('us_location')->select('state_code')->where('city', '=', $user->city)->orderBy('state_code', 'asc')->get();
        return view('patients.edit' , compact('user','diagnosis','qualifications','selected_disciplines','city_state'));
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
        $temp_number = str_replace(array("(", ")", "_", "-", " "), "", $request->input('mobile_number'));
        $request->merge(array('mobile_number' => $temp_number));
        $input = $request->input();
        $validator = validator::make($input,[
            'f_name' => 'required|string|max:20',
            'm_name' => 'nullable|string|max:20',
            'l_name' => 'required|string|max:20',
            'email' => 'required|email|string|max:60',
            'mobile_number' => 'required||min:10|max:10',
            'dob' => 'required',
            'gender' => 'required',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'street' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'addtional_info' => 'nullable|max:200',
            'qualification' => 'required',
            'long_term' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'language' => 'required',
            'pets' => 'required',
            'pets_description' => 'nullable|max:2000',
            'additional_info' => 'max:150'
        ],
        $messages = [
            'f_name.required'    => 'The First name is required.',
            'f_name.max'    => 'The First name may not be greater than 20 characters.',
            'm_name.max'    => 'The Middle name may not be greater than 20 characters.',
            'l_name.required'    => 'The Last name is required.',
            'l_name.max'    => 'The Last name may not be greater than 20 characters.',
            'pets.required'    => 'Pets is required.',
            'long_term.required'    => 'Long terms insurance is required.',
            'qualification.required'    => 'Discipline is required.',
        ]);
        if(isset($input['pets']) && $input['pets'] == 'yes'){
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
                $user->save();
                $destinationPath = config('image.user_image_path');
                $img = Image::make($image->getRealPath());
                $image->move($destinationPath, $input['profile_image']);
            }
                $user = User::findOrFail($id);
                $user->name = $input['f_name'].' '.$input['m_name'].' '.$input['l_name'];
                $user->email = $input['email'];
                $user->mobile_number = $input['mobile_number'];
                $user->city = $input['city'];
                $user->state = $input['state'];
                $user->street = $input['street'];
                $user->dob = date("Y-m-d", strtotime($input['dob']));
                $user->gender = $input['gender'];
                $user->save();

                $userProfile = PatientProfile::where('user_id',$id)->first();
                if($userProfile){
                    $userProfile['f_name'] = $input['f_name'];
                    $userProfile['m_name'] = $input['m_name'];
                    $userProfile['l_name'] = $input['l_name'];
                    $userProfile['pin_code'] = $input['pin_code'];
                    $userProfile['diagnose_id'] = $input['diagnose_id'];
                    $userProfile['availability'] = $input['availability'];
                    $userProfile['height'] = $input['height'];
                    $userProfile['weight'] = $input['weight'];
                    $userProfile['language'] = $input['language'];
                    $userProfile['disciplines'] = implode(',', $input['qualification']) ;
                    $userProfile['long_term'] = $input['long_term'] == 'yes'? 1 : 0;
                    $userProfile['pets'] = $input['pets'] == 'yes'? 1 : 0;
                    $userProfile['pets_description'] = $input['pets'] == 'yes'? $input['pets_description'] : '';
                    $userProfile['additional_info'] = $input['additional_info'];
                    $userProfile->save();
                }else{
                    $profile['user_id'] = $user->id;
                    $profile['f_name'] = $input['f_name'];
                    $profile['m_name'] = $input['m_name'];
                    $profile['l_name'] = $input['l_name'];
                    $profile['pin_code'] = $input['pin_code'];
                    $profile['diagnose_id'] = $input['diagnose_id'];
                    $profile['availability'] = $input['availability'];
                    $profile['height'] = $input['height'];
                    $profile['weight'] = $input['weight'];
                    $profile['language'] = $input['language'];
                    $profile['disciplines'] = implode(',', $input['qualification']) ;
                    $profile['long_term'] = $input['long_term'] == 'yes'? 1 : 0;
                    $profile['pets'] = $input['pets'] == 'yes'? 1 : 0;
                    $profile['pets_description'] = $input['pets_description'];
                    $profile['additional_info'] = $input['additional_info'];
                    $profile = PatientProfile::create($profile);
                }

                flash()->success('Client updated successfully');
                return redirect()->route('patients.index');
    }

    public function block($id){
        $user = User::find($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        if ($user->is_blocked)
            flash()->success("Client blocked successfully.");
        else
            flash()->success("Client Unblocked successfully.");

        return redirect()->route('patients.index');
    }

    public function create(){
        $diagnosis = Diagnose::get();
        $qualifications = Qualification::orderBy('name', 'asc')->get();
        return view('patients.create', compact('diagnosis','qualifications'));
    }

    public function store(Request $request){
        $temp_number = str_replace(array("(", ")", "_", "-", " "), "", $request->input('mobile_number'));
        $request->merge(array('mobile_number' => $temp_number));
        $input = $request->input();
        $validator = validator::make($input,[
            'f_name' => 'required|string|max:20',
            'm_name' => 'nullable|string|max:20',
            'l_name' => 'required|string|max:20',
            'email' => 'required|email|string|max:60|unique:users',
            'mobile_number' => 'required|unique:users|min:10|max:10',
            'dob' => 'required',
            'gender' => 'required',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'street' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'addtional_info' => 'nullable|max:200',
            'qualification' => 'required',
            'pets' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'language' => 'required',
            'pets_description' => 'max:2000',
            'long_term' => 'required',
            'additional_info' => 'max:150'
        ],
        $messages = [
            'f_name.required'    => 'The First name is required.',
            'f_name.max'    => 'The First name may not be greater than 20 characters.',
            'm_name.max'    => 'The Middle name may not be greater than 20 characters.',
            'l_name.required'    => 'The Last name is required.',
            'l_name.max'    => 'The Last name may not be greater than 20 characters.',
            'pets.required'    => 'Pets is required.',
            'long_term.required'    => 'Long terms insurance is required.',
            'qualification.required'    => 'Discipline is required.',
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

            $input['name'] = $input['f_name'].' '.$input['m_name'].' '.$input['l_name'];
            $input['role_id'] = 3;
            $input['email'] = $input['email'];
            $input['mobile_number'] = $input['mobile_number'];
            $input['city'] = $input['city'];
            $input['state'] = $input['state'];
            $input['street'] = $input['street'];
            $input['type'] = 'patient';
            $input['password'] = Hash::make('123456');
            $input['dob'] = date("Y-m-d", strtotime($input['dob']));
            $input['gender'] = $input['gender'];
            $patient = User::create($input);

            $profile['user_id'] = $patient->id;
            $profile['f_name'] = $input['f_name'];
            $profile['m_name'] = $input['m_name'];
            $profile['l_name'] = $input['l_name'];
            $profile['pin_code'] = $input['pin_code'];
            $profile['diagnose_id'] = $input['diagnose_id'];
            $profile['availability'] = $input['availability'];
            $profile['height'] = $input['height'];
            $profile['weight'] = $input['weight'];
            $profile['language'] = $input['language'];
            $profile['disciplines'] = implode(',', $input['qualification']) ;
            $profile['long_term'] = $input['long_term'] == 'yes'? 1 : 0;
            $profile['pets'] = $input['pets'] == 'yes'? 1 : 0;
            $profile['pets_description'] = $input['pets'] == 'yes'? $input['pets_description'] : '';
            $profile['additional_info'] = $input['additional_info'];
            $profile = PatientProfile::create($profile);

            flash()->success('New Client added successfully');
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
            $disciplines = explode(',', $user->patient->disciplines) ;
            foreach ($disciplines as $key => $value) {
                $disciplines_name[] = Qualification::where('id',$value)->first();
            }
        }else{
            $diagnosis = '';
            $disciplines = '';
            $disciplines_name ='';
        }
        return view('patients.view', compact('user','diagnosis','services','disciplines_name'));
    }

    public function download_excel(){
        return Excel::download(new PatientExport, 'Client_list.xlsx');
    }

    public function searchcity(Request $request){
        DB::enableQueryLog();
        $fieldval = $request->input('term');
        $search_zipx = array();

        $search_city = Us_location::select('city')->Where("city","like","{$fieldval}%")->groupBy('city')->orderBy("city","asc")->get();

        $response = array();
        $response['error'] = false;
        if(empty($search_city)){
            $response['error'] = true;
            $response['msg'] = 'Invalid City';
        }else{
            foreach($search_city as $row){
                array_push($response, $row->city);
            }
        }
        echo json_encode($response, true);
    }

    public function statefromcity(Request $request){
        $fieldval = $request->input('term');
        $search_city = Us_location::select('state_code')->Where("city","=","{$fieldval}")->orderBy("state_code","ASC")->distinct('state_code')->get();

        $response = array();
        $response['error'] = false;
        $response['list'] = array();
        if(empty($search_city)){
            $response['error'] = true;
            $response['msg'] = 'Invalid City';
        }else{
            foreach ($search_city as $row) {
                array_push($response['list'], $row->state_code);
            }
        }
        echo json_encode($response, true);
    }

    public function getzip(Request $request){
        $city = $request->input('city');
        $state = $request->input('state');
        $zipcode = DB::select( DB::raw("SELECT zip FROM `us_location` where city = '".$city."' and state_code = '".$state."'"));
        echo $zipcode[0]->zip;
    }
}
