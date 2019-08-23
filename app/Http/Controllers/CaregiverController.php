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
use App\Service_requests_attributes;
use DB;

class CaregiverController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $caregivers = User::select('users.*','service','min_price','max_price')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','>', '1')->where('type', 'caregiver')->orderBy('users.id', 'desc')->get();
        return view('caregiver.index', compact('caregivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $service_list = DB::table('services')->orderBy('title', 'asc')->get();
        $qualification = DB::table('qualifications')->orderBy('name', 'asc')->get();
        return view('caregiver.create', compact('service_list', 'qualification'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $input = $request->input(); 

        $validator =  Validator::make($input,[
            'fname' => 'required|string|max:255|min:4',
            'lname' => 'required|string|max:255|min:4',
            'email' => 'required|string|unique:users,email',
            'mobile_number' => 'required|unique:users,mobile_number',
            'service' => 'required|not_in:0',
            'password' => 'required|min:6',
            'gender' => 'required',
            'dob' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'non_service_zipcode' => 'required',
            'service_zipcode' => 'required',
            'description' => 'required',
            'qualification' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        $upload_image = '';
        $profile_image = $request->file('profile_image');
        if(!empty($profile_image)){
            $imageName = time().'.'.$profile_image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile_images');
            $imagePath = $destinationPath. "/".  $imageName;
            $profile_image->move($destinationPath, $imageName);
            $upload_image =  "/uploads/profile_images/".$imageName;
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
        $user->country = 'USA';
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
            $caregiver->middle_name = $input['mname'];
            $caregiver->last_name = $input['lname'];
            $caregiver->description = $input['description'];
            $caregiver->zipcode = $input['zipcode'];            
            $caregiver->save();

            $data = array();    //array to save caregiver attributes
            //save non servicable zip codes
            $zipcodes = trim($input['non_service_zipcode']);
            $zipcode_array = explode(", ", $zipcodes);                        
            foreach($zipcode_array as $zip){
                if(!empty(trim($zip))){
                    $data[] = array(
                        'caregiver_id' => $user->id,
                        'value' => $zip,
                        'type' => 'non_service_zipcodes'
                    );   
                }    
            }

            //save servicable zip codes
            $servicezipcode = trim($input['service_zipcode']);
            $zipcode_array = explode(", ", $servicezipcode);
            foreach($zipcode_array as $zip){
                if(!empty(trim($zip))){
                    $data[] = array(
                        'caregiver_id' => $user->id,
                        'value' => trim($zip),
                        'type' => 'service_zipcodes'
                    );   
                }    
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

        $user  = DB::table('users')->select('users.*', 'caregiver.height', 'caregiver.weight', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.description', 'caregiver.zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.type', '=', 1)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $user->services = DB::table('caregiver_attributes')->select('services.title')->Join('services', 'services.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('services.title', 'asc')->get();

        $user->qualification = DB::table('caregiver_attributes')->select('qualifications.name')->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'qualification')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('qualifications.name', 'asc')->get();

        $user->service_zipcodes = DB::table('caregiver_attributes')->select('us_location.zip', 'us_location.city')->Join('us_location', 'us_location.zip', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service_zipcodes')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('us_location.zip', 'asc')->get();

        $user->non_service_zipcodes = DB::table('caregiver_attributes')->select('us_location.zip', 'us_location.city')->Join('us_location', 'us_location.zip', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'non_service_zipcodes')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('us_location.zip', 'asc')->get();

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
        $user  = DB::table('users')->select('users.*', 'caregiver.first_name', 'caregiver.middle_name', 'caregiver.last_name', 'caregiver.height', 'caregiver.weight', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.description', 'caregiver.zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.type', '=', 1)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $user->services = DB::table('caregiver_attributes')->select('services.id')->Join('services', 'services.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('services.title', 'asc')->get();

        $user->qualification = DB::table('caregiver_attributes')->select('qualifications.id')->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'qualification')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('qualifications.name', 'asc')->get();

        $user->service_zipcodes = DB::table('caregiver_attributes')->select('us_location.zip', 'us_location.city')->Join('us_location', 'us_location.zip', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service_zipcodes')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('us_location.zip', 'asc')->get();

        $user->non_service_zipcodes = DB::table('caregiver_attributes')->select('us_location.zip', 'us_location.city')->Join('us_location', 'us_location.zip', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'non_service_zipcodes')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('us_location.zip', 'asc')->get();

        $service_list = DB::table('services')->orderBy('title', 'asc')->get();
        $qualification = DB::table('qualifications')->orderBy('name', 'asc')->get();
        $city_state = DB::table('us_location')->select('state_code')->where('city', '=', $user->city)->orderBy('state_code', 'asc')->get();

        return view('caregiver.edit', compact('user', 'qualification', 'service_list', 'city_state'));
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
            'first_name' => 'required|string|max:255|min:4',
            'last_name' => 'required|string|max:255|min:4',
            'email' => 'required|string',
            'mobile_number' => 'required',
            'service' => 'required|not_in:0',
            'gender' => 'required',
            'dob' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'non_service_zipcode' => 'required',
            'service_zipcode' => 'required',
            'description' => 'required',
            'qualification' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
        $user->country = 'USA';
        $user->gender = $input['gender'];
        $user->dob = date("Y-m-d", strtotime($input['dob']));
        if ($input['password'] != null) {
            $user->password = Hash::make($input['password']);
        }

        if(!empty($request->file('profile_image'))){
            $profile_image = $request->file('profile_image');
            $imageName = time().'.'.$profile_image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile_images');
            $imagePath = $destinationPath. "/".  $imageName;
            $profile_image->move($destinationPath, $imageName);
            $user->profile_image =  "/uploads/profile_images/".$imageName;
        }
        $user->save();

        $caregiverid = DB::table('caregiver')->select('id')->where('user_id','=', $id)->first();
        $caregiver = Caregiver::findOrFail($caregiverid->id);
        $caregiver->service = '';
        $caregiver->min_price = $input['min_price'];
        $caregiver->max_price = $input['max_price'];
        $caregiver->first_name = $input['first_name'];
        $caregiver->middle_name = $input['middle_name'];
        $caregiver->last_name = $input['last_name'];
        $caregiver->height = $input['height'];
        $caregiver->weight = $input['weight'];
        $caregiver->description = $input['description'];
        $caregiver->zipcode = $input['zipcode']; 
        $caregiver->save();

        //remove all old zipcode, services, qualifications, non service zipcode, srvice zipcode
        DB::table('caregiver_attributes')->where('caregiver_id', '=', $id)->delete();

        //now save new changes
        $data = array();    //array to save caregiver attributes
        //save non servicable zip codes
        $zipcodes = $input['non_service_zipcode']." ";
        $zipcode_array = explode(", ", $zipcodes); 
        foreach($zipcode_array as $zip){
            if(!empty(trim($zip))){
                $data[] = array(
                    'caregiver_id' => $id,
                    'value' => $zip,
                    'type' => 'non_service_zipcodes'
                );   
            }    
        }

        //save servicable zip codes
        $servicezipcode = $input['service_zipcode']." ";
        $zipcode_array = explode(", ", $servicezipcode);
        foreach($zipcode_array as $zip){
            if(!empty(trim($zip))){
                $data[] = array(
                    'caregiver_id' => $id,
                    'value' => trim($zip),
                    'type' => 'service_zipcodes'
                );   
            }    
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
        //
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
        //download caregiver list
        $usre_data = DB::table('users')->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.description', 'caregiver.zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->orderBy('users.id', 'desc')->get();
        
        $filename = "Caregivers.xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        if(empty($usre_data)){
            echo 'No records Found...';
        }{
            $isPrintHeader = false;
            $header = array(
                'S. No.', 
                'Name', 
                'Email', 
                'Mobile No.',
                'Gender',
                'Date Of Birth',
                'Street',
                'Zip Code',
                'City',
                'State',
                'Country',
                'Price Range',
                'Created On',
            );

            $count = 1;
            foreach ($usre_data as $row) {
                if (!$isPrintHeader) {
                    echo implode("\t", array_values($header)) . "\n";
                    $isPrintHeader = true;
                }

                $temp = array(
                    $count.".", 
                    ucfirst(str_replace(",", " ", $row->name)), 
                    $row->email,
                    $row->mobile_number,
                    $row->gender,
                    date("d-m-Y", strtotime($row->dob)),
                    ucfirst($row->location).", ".$row->city.", ".$row->state.", ".$row->country.", ".$row->zipcode,
                    "$".$row->min_price." - $".$row->max_price,
                    date("d-m-Y", strtotime($row->created_at))
                );
                echo implode("\t", array_values($temp)) . "\n";
                $count++;
            }
        }
        exit(); 
    }
}