<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Caregiver;
use App\Us_location;
use App\Nonservice_zipcode;
use Validator;
use App\State;
use App\CaregiverAttribute;
use App\Service_requests_attributes;
use DB;
use App\Mail\MailHelper;
use Illuminate\Support\Facades\Mail;

use App\Exports\CaregiverExport;
use Maatwebsite\Excel\Facades\Excel;

class CaregiverController extends Controller{
    public function __construct(){ 
        $this->middleware('preventBackHistory');
        $this->middleware('auth'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $caregivers = User::select('users.*','service','min_price','max_price', 'language')
        ->Join('caregiver', 'caregiver.user_id', '=', 'users.id')
        ->where('users.id','>', '1')->where('type', 'caregiver')->orderBy('users.id', 'desc')->get();
        foreach ($caregivers as $key => $value) {
          $value->qualification = DB::table('caregiver_attributes')
          ->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')
          ->where('caregiver_attributes.type', '=', 'qualification')
          ->where('caregiver_attributes.caregiver_id', '=', $value->id)
          ->pluck('qualifications.name')->toArray();
        }
        return view('caregiver.index', compact('caregivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $service_list = DB::table('services')->orderBy('title', 'asc')->get();
        $qualification = DB::table('qualifications')->where('is_blocked', '=', '0')->orderBy('name', 'asc')->get();
        $service_area_list = DB::table('county_areas')->select('id', 'county', 'area')->where('area', '!=', '0')->where('is_area_blocked' , '1')->orderBy('area', 'asc')->get();
        return view('caregiver.create', compact('service_list', 'service_area_list', 'qualification'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $input = $request->input();
        $profile_image = $request->file('profile_image');

        //make validation
        $validator =  Validator::make($input,[
            'fname' => 'required|string|max:40',
            'lname' => 'required|string|max:40',
            'email' => 'email|required|string|unique:users,email',
            'mobile_number' => 'required|unique:users,mobile_number|regex:/^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/',
            'service' => 'required|not_in:0',
            'password' => 'required|min:6',
            'gender' => 'required',
            'language' => 'required',
            'dob' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1|gt:min_price',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'service_area' => 'required',
            'non_service_area' => 'required',
            'description' => 'required|max:150',
            'qualification' => 'required|not_in:0',
        ],
        [   
            'max_price.gt' => 'The max price must be greater than min price.',
            'mobile_number.regex' => 'The mobile number must be 10 digits.'
        ]);

        //show custome name of field in validation errors
        $attributeNames = array(
           'fname' => 'first name',
           'lname' => 'last name',
           'mname' => 'middel name',
           'dob' => 'date of birth',
           'location' => 'street',
           'zipcode' => 'zip code'
        );
        $validator->setAttributeNames($attributeNames);

        $upload_image ='';
        if(!empty($request->file('profile_image'))){
            $profile_image = $request->file('profile_image');
            $prop['ext'] = $profile_image->getClientOriginalExtension();

            //make image validation
            if(!in_array($profile_image->getClientOriginalExtension(), array('jpeg', 'png', 'jpg'))){
                $validator->after(function($validator){
                    $validator->errors()->add('profile_image', 'Only jpeg, png, jpg type are valid for image');
                });
            }else if(2097152 < $profile_image->getSize()){
                $validator->after(function($validator){
                    $validator->errors()->add('profile_image', 'image size should not be greater then 2MB.');
                });
            }else{
                $imageName = time().'.'.$profile_image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/profile_images');
                $imagePath = $destinationPath. "/".  $imageName;
                $profile_image->move($destinationPath, $imageName);
                $upload_image =  "/uploads/profile_images/".$imageName;
            }
        }

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        $name = $input['fname'];
        if(!empty($input['mname'])){
            $name .= " ".$input['mname'];
        }
        $name .= " ".$input['lname'];

        $user = new User();
        $user->role_id = 2;
        $user->name = $name;
        $user->email = $input['email'];
        $user->email_verified = 1;
        $user->mobile_number = $input['mobile_number'];
        $user->country_code = '+1';
        $user->type = 1;
        $user->mobile_number_verified = 1;
        $user->gender = $input['gender'];
        $user->dob = date("Y-m-d", strtotime($input['dob']));
        $user->profile_image = $upload_image;
        $user->location = $input['location'];
        $user->city = $input['city'];
        $user->state = $input['state'];
        $user->password = Hash::make($input['password']);
        if($user->save()){
            //save caregiver profile info
            $caregiver = new Caregiver();
            $caregiver->user_id = $user->id;
            $caregiver->service = 'NAN';
            $caregiver->min_price = $input['min_price'];
            $caregiver->max_price = $input['max_price'];
            $caregiver->height = $input['height'];
            $caregiver->weight = $input['weight'];
            $caregiver->first_name = $input['fname'];
            if(!empty($input['mname'])){
                $caregiver->middle_name = $input['mname'];
            }

            $caregiver->last_name = $input['lname'];
            $caregiver->language = $input['language'];
            $caregiver->description = $input['description'];
            $caregiver->zipcode = $input['zipcode'];
            $caregiver->save();

            $data = array();    //array to save caregiver attributes

            //save servicable area
            $serivice_area = $input['service_area'];
            foreach($serivice_area as $area){
                $data[] = array(
                    'caregiver_id' => $user->id,
                    'value' => $area,
                    'type' => 'service_area'
                );
            }

            //save no servicable area
            $non_service_area = $input['non_service_area'];
            foreach($non_service_area as $area){
                $data[] = array(
                    'caregiver_id' => $user->id,
                    'value' => $area,
                    'type' => 'non_service_area'
                );
            }

            //save qualification
            foreach($input['qualification'] as $value){
                $data[] = array(
                    'caregiver_id' => $user->id,
                    'value' => $value,
                    'type' => 'qualification'
                );
            }

            //save services
            foreach($input['service'] as $value){
                $data[] = array(
                    'caregiver_id' => $user->id,
                    'value' => $value,
                    'type' => 'service'
                );
            }

            DB::table('caregiver_attributes')->insert($data);

            //send mail about reset password
            if($input['issentmail'] == '1'){
                $objDemo = new \stdClass();
                $objDemo->sender = env('APP_NAME');
                $objDemo->receiver = ucfirst($user->name);
                $objDemo->type = 'password_on_mail';
                $objDemo->format = 'basic';
                $objDemo->subject = '24*7 Nursing : Password Mail';
                $objDemo->mail_from = env('MAIL_FROM_EMAIL');
                $objDemo->mail_from_name = env('MAIL_FROM_NAME');
                $objDemo->email = $user->email;
                $objDemo->password = $input['password'];
                $issemd = Mail::to($input['email'])->send(new MailHelper($objDemo));
            }

            //redirect to index page.
            flash()->success('New Caregiver Add successfull');
            return redirect()->route('caregiver.index');
        }else{
            flash()->success('There is some error, please try again..');
            return redirect()->route('caregiver.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $user  = DB::table('users')->select('users.*', 'caregiver.height', 'caregiver.weight', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.description', 'caregiver.zipcode', 'caregiver.language')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.type', '=', 1)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $user->services = DB::table('caregiver_attributes')->select('services.title')->Join('services', 'services.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('services.title', 'asc')->get();

        $user->qualification = DB::table('caregiver_attributes')->select('qualifications.name')->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'qualification')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('qualifications.name', 'asc')->get();

        $user->service_area = DB::table('caregiver_attributes')->select('county_areas.area')->Join('county_areas', 'county_areas.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service_area')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('county_areas.area', 'asc')->get();

        $user->non_service_area = DB::table('caregiver_attributes')->select('county_areas.area')->Join('county_areas', 'county_areas.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'non_service_area')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('county_areas.area', 'asc')->get();

        $services = DB::table('service_requests_attributes AS sra')
                    ->join('service_requests' , 'service_requests.id' , 'service_request_id')
                    ->join('services' ,'services.id','service_requests.service')
                    ->join('users','users.id','service_requests.user_id')
                    ->select('service_requests.*','services.title','users.name')
                    ->where('value',$id)
                    ->where('sra.type','final_caregiver')
                    ->get();

        return view('caregiver.view', compact('user' , 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $user  = DB::table('users')->select('users.*', 'caregiver.first_name', 'caregiver.middle_name', 'caregiver.last_name', 'caregiver.height', 'caregiver.weight', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.description', 'caregiver.zipcode', 'caregiver.language')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.type', '=', 1)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $user->services = DB::table('caregiver_attributes')->select('services.id')->Join('services', 'services.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('services.title', 'asc')->get();

        $user->qualification = DB::table('caregiver_attributes')->select('qualifications.id')->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'qualification')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('qualifications.name', 'asc')->get();

        $user->service_area  = array();
        $service_area = DB::table('caregiver_attributes')->select('county_areas.id', 'county_areas.area')->Join('county_areas', 'county_areas.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service_area')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('county_areas.area', 'asc')->get();
        if(!empty($service_area)){
            $temp = array();
            foreach ($service_area as $key => $value) {
                $temp[] = $value->id;
            }
            $user->service_area = $temp;
        }

        $user->non_service_area = array();
        $non_service_area = DB::table('caregiver_attributes')->select('county_areas.id', 'county_areas.area')->Join('county_areas', 'county_areas.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'non_service_area')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('county_areas.area', 'asc')->get();
        if(!empty($non_service_area)){
            $temp = array();
            foreach ($non_service_area as $key => $value) {
                $temp[] = $value->id;
            }
            $user->non_service_area = $temp;
        }

        $service_list = DB::table('services')->orderBy('title', 'asc')->get();

        $qualification = DB::table('qualifications')->where('is_blocked', '=', '0')->orderBy('name', 'asc')->get();

        $city_state = DB::table('us_location')->select('state_code')->where('city', '=', $user->city)->where('zip', '=', $user->zipcode)->orderBy('state_code', 'asc')->get();

        $service_area_list = DB::table('county_areas')->select('id', 'county', 'area')->where('is_area_blocked' , '1')->where('area', '!=', '0')->orderBy('area', 'asc')->get();

        return view('caregiver.edit', compact('user', 'qualification', 'service_list', 'city_state', 'service_area_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $input = $request->input();
        $validator =  Validator::make($input,[
            'first_name' => 'required|string|max:40',
            'last_name' => 'required|string|max:40',
            'email' => 'email|required|string',
            'mobile_number' => 'required|regex:/^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/',
            'service' => 'required|not_in:0',
            'gender' => 'required',
            'language' => 'required',
            'dob' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1|gt:min_price',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'service_area' => 'required',
            'non_service_area' => 'required',
            'description' => 'required|max:150',
            'qualification' => 'required|not_in:0',
        ],
        [
            'max_price.gt' => 'The max price must be greater than min price.',
            'mobile_number.regex' => 'The mobile number must be 10 digits.'
        ]);

        //show custome name of field in validation errors
        $attributeNames = array(
           'fname' => 'first name',
           'lname' => 'last name',
           'mname' => 'middel name',
           'dob' => 'date of birth',
           'location' => 'street',
           'zipcode' => 'zip code'
        );
        $validator->setAttributeNames($attributeNames);

        $upload_image ='';
        if(!empty($request->file('profile_image'))){
            $profile_image = $request->file('profile_image');
            $prop['ext'] = $profile_image->getClientOriginalExtension();

            //make image validation
            if(!in_array($profile_image->getClientOriginalExtension(), array('jpeg', 'png', 'jpg'))){
                $validator->after(function($validator){
                    $validator->errors()->add('profile_image', 'Only jpeg, png, jpg type are valid for image');
                });
            }else if(2097152 < $profile_image->getSize()){
                $validator->after(function($validator){
                    $validator->errors()->add('profile_image', 'image size should not be greater then 2MB.');
                });
            }else{
                $imageName = time().'.'.$profile_image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/profile_images');
                $imagePath = $destinationPath. "/".  $imageName;
                $profile_image->move($destinationPath, $imageName);
                $upload_image =  "/uploads/profile_images/".$imageName;
            }
        }

        if ($validator->fails()) {

            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        $name = $input['first_name'];
        if(!empty($input['middle_name'])){
            $name .= " ".$input['middle_name'];
        }
        $name .= " ".$input['last_name'];

        $user = User::findOrFail($id);
        $user->role_id = 2;
        $user->name = $name;
        $user->email = $input['email'];
        $user->email_verified = 1;
        $user->mobile_number = $input['mobile_number'];
        $user->country_code = '+1';
        $user->type = 1;
        $user->mobile_number_verified = 1;
        $user->location = $input['location'];
        $user->city = $input['city'];
        $user->state = $input['state'];
        $user->gender = $input['gender'];
        $user->dob = date("Y-m-d", strtotime($input['dob']));

        //upload file
        if(!empty($upload_image)){
            $user->profile_image =  "$upload_image";
        }

        $user->save();

        //send mail about reset password
        if(isset($input['issentmail']) && $input['issentmail'] == '1'){
            $objDemo = new \stdClass();
            $objDemo->sender = env('APP_NAME');
            $objDemo->receiver = ucfirst($user->name);
            $objDemo->type = 'password_on_mail';
            $objDemo->format = 'basic';
            $objDemo->subject = '24*7 Nursing : Password Mail';
            $objDemo->mail_from = env('MAIL_FROM_EMAIL');
            $objDemo->mail_from_name = env('MAIL_FROM_NAME');
            $objDemo->email = $user->email;
            $objDemo->password = $input['password'];
            $issemd = Mail::to($input['email'])->send(new MailHelper($objDemo));
        }

        $caregiverid = DB::table('caregiver')->select('id')->where('user_id','=', $id)->first();
        $caregiver = Caregiver::findOrFail($caregiverid->id);
        $caregiver->service = '';
        $caregiver->min_price = $input['min_price'];
        $caregiver->max_price = $input['max_price'];
        $caregiver->first_name = $input['first_name'];
        if(!empty($input['middle_name'])){
            $caregiver->middle_name = $input['middle_name'];
        }
        $caregiver->last_name = $input['last_name'];
        $caregiver->height = $input['height'];
        $caregiver->weight = $input['weight'];
        $caregiver->language = $input['language'];
        $caregiver->description = $input['description'];
        $caregiver->zipcode = $input['zipcode'];
        $caregiver->save();

        //remove all old zipcode, services, qualifications, non service zipcode, srvice zipcode
        DB::table('caregiver_attributes')->where('caregiver_id', '=', $id)->delete();

        //now save new changes
        $data = array();    //array to save caregiver attributes

        //save servicable area
        $serivice_area = $input['service_area'];
        foreach($serivice_area as $area){
            $data[] = array(
                'caregiver_id' => $user->id,
                'value' => $area,
                'type' => 'service_area'
            );
        }

        //save no servicable area
        $non_service_area = $input['non_service_area'];
        foreach($non_service_area as $area){
            $data[] = array(
                'caregiver_id' => $user->id,
                'value' => $area,
                'type' => 'non_service_area'
            );
        }

        //save qualification
        foreach($input['qualification'] as $value){
            $data[] = array(
                'caregiver_id' => $id,
                'value' => $value,
                'type' => 'qualification'
            );
        }

        //save services
        foreach($input['service'] as $value){
            $data[] = array(
                'caregiver_id' => $id,
                'value' => $value,
                'type' => 'service'
            );
        }            

        DB::table('caregiver_attributes')->insert($data);

        flash()->success("User detail updated successfully.");
        return redirect()->route('caregiver.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        $user = User::findOrFail($id);
        $caregiver = Caregiver::where('user_id' , $id)->delete();
        $attributes = CaregiverAttribute::where('caregiver_id' , $id)->delete();

        if ($user->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Caregiver deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Caregiver can not be deleted.Please try again',
            );
        }
        return json_encode($response);
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

    public function searchzip(Request $request){
        DB::enableQueryLog();

        $fieldval = $request->input('term');
        $search_zipx = array();
        if (strpos($fieldval, ', ') !== false) {
            $temp = explode(', ', $fieldval);
            $keyword = trim(substr(strrchr($fieldval, ", "), 1));
            array_pop($temp);
            $search_zipx = Us_location::Where("zip", "like", "{$keyword}%")->WhereNotIn("zip", $temp)->orderBy("zip", "asc")->get();
        }else{
            $search_zipx = Us_location::Where("zip", "like", "{$fieldval}%")->orderBy("zip", "asc")->get();
        }
        $temp = array();
        foreach ($search_zipx as $row) {
            array_push($temp, "$row->zip");
        }
        echo json_encode($temp, true);
    }

    public function locationfromzip(Request $request){
        $zipcode = $request->input('zipcode');
        $search_zipx = DB::select( DB::raw("SELECT * FROM `us_location` where zip = '".$zipcode."'"));

        $response = array();
        $response['error'] = false;
        if(empty($search_zipx)){
            $response['error'] = true;
            $response['msg'] = 'Invalid zipcode';
        }else{
            $response['city'] = $search_zipx[0]->city;
            $response['state'] = $search_zipx[0]->state_code;
        }
        echo json_encode($response, true);
    }

    public function getzip(Request $request){
        $city = $request->input('city');
        $state = $request->input('state');
        $zipcode = DB::select( DB::raw("SELECT zip FROM `us_location` where city = '".$city."' and state_code = '".$state."'"));
        echo $zipcode[0]->zip;
    }

    public function locationfromcity(Request $request){
        $zipcode = $request->input('city');
        $search_zipx = DB::select( DB::raw("SELECT * FROM `us_location` where zip = '".$zipcode."'"));

        $response = array();
        $response['error'] = false;
        if(empty($search_zipx)){
            $response['error'] = true;
            $response['msg'] = 'Invalid zipcode';
        }else{
            $response['city'] = $search_zipx[0]->city;
            $response['state'] = $search_zipx[0]->state_code;
        }
        echo json_encode($response, true);
    }

    public function blocked($id){
        $user = User::find($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        if ($user->is_blocked)
            flash()->success("Caregiver blocked successfully.");
        else
            flash()->success("Caregiver Unblocked successfully.");

        return redirect()->route('caregiver.index');
    }

    public function download_excel(){
        return Excel::download(new CaregiverExport, 'Caregiver_list.xlsx');
    }

    public function set_password($token){
        $user  = DB::table('users')->select('users.*')->where('email_activation_token','=', $token)->first();
        return view('resetpassword', compact('user'));
    }

    public function savepassword(Request $request){
        $input = $request->input();

        $validator =  Validator::make($input,[
            'password' => 'required|string|max:255|min:8|required_with:cpassword|same:cpassword',
            'cpassword' => 'required|string|max:255|min:8',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user_password = Hash::make($input['password']);
        $service_request = DB::table('users')->where('email_activation_token','=',$input['token'])->update(array('password' => $user_password));
        $issuccess = true;
        return view('resetpassword', compact('issuccess'));
    }
}
