<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Diagnose;
use Validator;
use App\PatientProfile;
use App\Qualification;
use App\Us_location;
use App\Booking;
use App\ContactUs;
use Carbon\Carbon;
use Image;
use DB;
use App\UserRelation;
use Illuminate\Http\Request;
use App\Exports\PatientExport;
use Maatwebsite\Excel\Facades\Excel;

class PatientsController extends Controller{
    public function __construct(){ 
        $this->middleware('preventBackHistory');
        $this->middleware('auth'); 
    }
    
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

        $diagnosis_selected = Diagnose::where('id',$user->patient? $user->patient->diagnose_id : '');
        $diagnosis = Diagnose::where('is_blocked','0')->union($diagnosis_selected)->get();
        $selected_disciplines = explode(',', $user->patient? $user->patient->disciplines: '');

        $qualifications_selected = Qualification::whereIn('id',$selected_disciplines);
        $qualifications = Qualification::where('is_blocked','0')->union($qualifications_selected)->get();

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
        $input = $request->input();
        $validator = validator::make($input,[
            'f_name' => 'required|string|max:20',
            'm_name' => 'nullable|string|max:20',
            'l_name' => 'required|string|max:20',
            'email' => 'required|email|string|max:60',
            'mobile_number' => 'required|regex:/^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/',
            'dob' => 'required',
            'gender' => 'required',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'street' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg',
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
            'mobile_number.regex' => 'The mobile number must be 10 digits.'
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
                $user->mobile_number = preg_replace('`-`', '', $input['mobile_number']);
                $user->city = $input['city'];
                $user->state = $input['state'];
                $user->street = $input['street'];
                $user->dob = date("Y-m-d", strtotime($input['dob']));
                $user->gender = $input['gender'];
                $user->save();

                $patient = PatientProfile::where('user_id',$id)->first();
                $patient['f_name'] = $input['f_name'];
                $patient['m_name'] = $input['m_name'];
                $patient['l_name'] = $input['l_name'];
                $patient['pin_code'] = $input['pin_code'];
                $patient['diagnose_id'] = $input['diagnose_id'];
                $patient['availability'] = $input['availability'];
                $patient['height'] = $input['height'];
                $patient['weight'] = $input['weight'];
                $patient['language'] = $input['language'];
                $patient['disciplines'] = implode(',', $input['qualification']) ;
                $patient['long_term'] = $input['long_term'] == 'yes'? 1 : 0;
                $patient['pets'] = $input['pets'] == 'yes'? 1 : 0;
                $patient['pets_description'] = $input['pets'] == 'yes'? $input['pets_description'] : '';
                $patient['additional_info'] = $input['additional_info'];
                $patient->save();

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
        $diagnosis = Diagnose::where('is_blocked','0')->get();
        $qualifications = Qualification::where('is_blocked','0')->orderBy('name', 'asc')->get();
        return view('patients.create', compact('diagnosis','qualifications'));
    }

    public function store(Request $request){
        $input = $request->input();
        $validator = validator::make($input,[
            'f_name' => 'required|string|max:20',
            'm_name' => 'nullable|string|max:20',
            'l_name' => 'required|string|max:20',
            'email' => 'required|email|string|max:60|unique:users',
            'mobile_number' => 'required|unique:users|regex:/^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/',
            'dob' => 'required',
            'gender' => 'required',
            'pin_code' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'street' => 'required|string',
            'diagnose_id' => 'required',
            'availability' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg',
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
            'mobile_number.regex' => 'The mobile number must be 10 digits.'
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
            $input['type'] = 'patient';
            $input['mobile_number'] = preg_replace('`-`', '', $input['mobile_number']);
            $input['dob'] = date("Y-m-d", strtotime($input['dob']));
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
        $services = Booking::where('user_id', $id)->get();
        $diagnosis = Diagnose::where('id',$user->patient->diagnose_id)->first();
        $disciplines = explode(',', $user->patient->disciplines) ;

        foreach ($disciplines as $key => $value) {
            $disciplines_name[] = Qualification::where('id',$value)->first();
        }
         
        return view('patients.view', compact('user','diagnosis','services','disciplines_name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        $user = User::findOrFail($id);
        $patient = PatientProfile::where('user_id' , $id)->delete();
        $booking = Booking::where('user_id' , $id)->delete();
        $user_relation = UserRelation::where('user_id' , $id)->delete();
        $contact = ContactUs::where('user_id', $id)->delete();

        if ($user->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Patient deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Patient can not be deleted.Please try again',
            );
        }
        return json_encode($response);
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