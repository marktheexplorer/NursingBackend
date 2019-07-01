<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Caregiver;
use App\Nonservice_zipcode;
use Validator;
use App\State;
use DB;

class CaregiverController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $caregivers = DB::table('users')->select('users.id', 'name', 'email', 'mobile_number', 'profile_image', 'is_blocked', 'users.created_at', 'service', 'min_price', 'max_price', 'gender')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','>', '1')->where('users.type', '=', 1)->orderBy('users.id', 'desc')->get();
        return view('caregiver.index', compact('caregivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $service_list = DB::table('services')->orderBy('title', 'asc')->get();
        return view('caregiver.create', compact(('service_list')));
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
            'name' => 'required|string|max:255|min:4',
            'email' => 'required|string|unique:users,email',
            'mobile_number' => 'required|unique:users,mobile_number|numeric',
            'service' => 'required',
            'password' => 'required|min:6',
            'gender' => 'required',
            'dob' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'non_service_zipcode' => 'required',
            'description' => 'required',
            //'profile_image' => 'image',
            //'profile_image' => 'dimensions:min_width=150,min_height=150|image',
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
            $upload_image =  "/uploads/profile_images/".$imageName;
        }
     
        $user = new User();
        $user->role_id = 2;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->email_verified = 1;
        $user->mobile_number = $input['mobile_number'];
        $user->country_code = '+1';
        $user->type = 1;
        $user->mobile_number_verified = 1;
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
            $caregiver->service = $input['service'];
            $caregiver->min_price = $input['min_price'];
            $caregiver->max_price = $input['max_price'];
            $caregiver->description = $input['description'];
            $caregiver->gender = $input['gender'];
            $caregiver->dob = date("Y-m-d", strtotime($input['dob']));
            $caregiver->zipcode = $input['zipcode'];            
            $caregiver->save();

            //save non servicable zip codes
            $zipcodes = trim($input['non_service_zipcode']);
            $zipcode_array = explode(",", $zipcodes);
            $data = array();
            foreach($zipcode_array as $zip){
                //save non servicable zip codes
                $data[] = array(
                    'caregiver_id' => $user->id,
                    'zipcode' => $zip
                );   
            }
            DB::table('nonservice_zipcode')->insert($data);

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
        $user  = DB::table('users')->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.gender', 'caregiver.description', 'caregiver.gender', 'caregiver.dob', 'caregiver.zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.type', '=', 1)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $nonservice_zipcode = DB::table('nonservice_zipcode')->where('caregiver_id', $id)->get();
        $service = DB::table('services')->select('title')->where('id', $user->service)->first();
        $user->service = $service->title;
        return view('caregiver.view', compact('user', 'nonservice_zipcode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $user  = DB::table('users')->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price', 'caregiver.gender', 'caregiver.description', 'caregiver.gender', 'caregiver.dob', 'caregiver.zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.type', '=', 1)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $user->nonservice_zipcode = DB::table('nonservice_zipcode')->where('caregiver_id', $id)->get();
        $service_list = DB::table('services')->orderBy('title', 'asc')->get();
        return view('caregiver.edit', compact('user', 'nonservice_zipcode', 'service_list'));
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
            'name' => 'required|string|max:255|min:4',
            'email' => 'required|string',
            'mobile_number' => 'required|between:8,15',
            'service' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'non_service_zipcode' => 'required',
            'description' => 'required',
            //'profile_image' => 'image',
            //'profile_image' => 'dimensions:min_width=150,min_height=150|image',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = User::findOrFail($id);
        $user->role_id = 2;
        $user->name = $input['name'];
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
        $caregiver->service = $input['service'];
        $caregiver->min_price = $input['min_price'];
        $caregiver->max_price = $input['max_price'];
        $caregiver->description = $input['description'];
        $caregiver->gender = $input['gender'];
        $caregiver->dob = date("Y-m-d", strtotime($input['dob']));
        $caregiver->zipcode = $input['zipcode'];            
        $caregiver->save();

        //remove all old zip or add new zip
        DB::table('nonservice_zipcode')->where('caregiver_id', '=', $id)->delete();

        //now save new changes
        $zipcodes = trim($input['non_service_zipcode']);
        $zipcode_array = explode(",", $zipcodes);
        $data = array();
        foreach($zipcode_array as $zip){
            //save non servicable zip codes
            $data[] = array(
                'caregiver_id' => $id,
                'zipcode' => $zip
            );   
        }
        DB::table('nonservice_zipcode')->insert($data);

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

    public function searchzip(Request $request){
        $keyword = $request->input('term');
        $search_zipx = DB::select( DB::raw("SELECT zip FROM `us_location` as up where zip LIKE '$keyword%'")); 
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
}