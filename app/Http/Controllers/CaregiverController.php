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
use DB;
use Image;
use App\Exports\CaregiverExport;
use Maatwebsite\Excel\Facades\Excel;
use App\AssignedCaregiver;
use App\Booking;
use App\ContactUs;

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
    public function index() 
    {
        $caregivers = User::select('users.*','service','min_price','max_price')
        ->Join('caregiver', 'caregiver.user_id', '=', 'users.id')
        ->where('users.id','>', '1')->where('role_id', '2')->orderBy('users.id', 'desc')->get();

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
    public function create()
    {
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
        $input['mobile_number'] = preg_replace('`-`', '', $input['mobile_number']);

        //make validation
        $validator =  Validator::make($input,[
            'f_name' => 'required|string|max:40',
            'l_name' => 'required|string|max:40',
            'email' => 'email|required|string|unique:users',
            'mobile_number' => 'required|unique:users',
            'service' => 'required|not_in:0',
            'gender' => 'required',
            'language' => 'required',
            'dob' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1|gt:min_price',
            'street' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'service_area' => 'required',
            'non_service_area' => 'required',
            'additional_info' => 'max:150',
            'qualification' => 'required|not_in:0',
            'country_code' => 'required',
        ],
        [   
            'max_price.gt' => 'The max price must be greater than min price.',
        ]);

        //show custome name of field in validation errors
        $attributeNames = array(
           'f_name' => 'first name',
           'l_name' => 'last name',
           'm_name' => 'middle name',
           'dob' => 'date of birth',
           'zipcode' => 'zip code'
        );
        $validator->setAttributeNames($attributeNames);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->has('profile_image') && ($request->file('profile_image') != null)) {
            $image = $request->file('profile_image');
            $input['profile_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = config('image.user_image_path');
            $img = Image::make($image->getRealPath());
            $image->move($destinationPath, $input['profile_image']);
        }

        $input['role_id'] = 2;
        $input['dob'] = date("Y-m-d", strtotime($input['dob']));
        $user = User::create($input);

        if($user){
            //save caregiver profile info
            $caregiver = new Caregiver();
            $caregiver->user_id = $user->id;
            $caregiver->service = 'NAN';
            $caregiver->min_price = $input['min_price'];
            $caregiver->max_price = $input['max_price'];
            $caregiver->save();

            $data = array();    //array to save caregiver attributes

            //save servicable area
            foreach($input['service_area'] as $area){
                $data[] = array(
                    'caregiver_id' => $user->id,
                    'value' => $area,
                    'type' => 'service_area'
                );
            }

            //save no servicable area
            foreach($input['non_service_area'] as $area){
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

            //redirect to index page.
            flash()->success('New Caregiver added successfully');
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
        $user  = DB::table('users')->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.role_id', '=', 2)->orderBy('users.id', 'desc')->first();
        if(empty($user)){
            flash()->error('Un-authorized user.');
            return redirect()->route('caregiver.index');
        }

        $user->services = DB::table('caregiver_attributes')->select('services.title')->Join('services', 'services.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('services.title', 'asc')->get();

        $user->qualification = DB::table('caregiver_attributes')->select('qualifications.name')->Join('qualifications', 'qualifications.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'qualification')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('qualifications.name', 'asc')->get();

        $user->service_area = DB::table('caregiver_attributes')->select('county_areas.area')->Join('county_areas', 'county_areas.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'service_area')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('county_areas.area', 'asc')->get();

        $user->non_service_area = DB::table('caregiver_attributes')->select('county_areas.area')->Join('county_areas', 'county_areas.id', '=', 'caregiver_attributes.value')->where('caregiver_attributes.type', '=', 'non_service_area')->where('caregiver_attributes.caregiver_id', '=', $id)->orderBy('county_areas.area', 'asc')->get();

        $caregiver = Caregiver::where('user_id' , $id)->first();
        $services = AssignedCaregiver::where('caregiver_id',$caregiver->id)->where('status','final')->with('booking')->get();
        return view('caregiver.view', compact('user' , 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $user  = DB::table('users')->select('users.*', 'caregiver.service', 'caregiver.min_price', 'caregiver.max_price')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->where('users.id','=', $id)->where('users.role_id', '=', 2)->orderBy('users.id', 'desc')->first();
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
            'f_name' => 'required|string|max:40',
            'l_name' => 'required|string|max:40',
            'email' => 'email|required|string|unique:users,email,'.$id,
            'mobile_number' => 'required|regex:/^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/|unique:users,mobile_number,'.$id,
            'service' => 'required|not_in:0',
            'gender' => 'required',
            'language' => 'required',
            'dob' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'min_price' => 'required|min:0',
            'max_price' => 'required|min:1|gt:min_price',
            'street' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'service_area' => 'required',
            'non_service_area' => 'required',
            'additional_info' => 'max:150',
            'qualification' => 'required|not_in:0',
            'country_code' => 'required',
        ],
        [
            'max_price.gt' => 'The max price must be greater than min price.',
            'mobile_number.regex' => 'The mobile number must be 10 digits.'
        ]);

        //show custome name of field in validation errors
        $attributeNames = array(
           'f_name' => 'first name',
           'l_name' => 'last name',
           'm_name' => 'middle name',
           'dob' => 'date of birth',
           'zipcode' => 'zip code'
        );
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
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
        $user->f_name = $input['f_name'];
        $user->m_name = $input['m_name'];
        $user->l_name = $input['l_name'];
        $user->email = $input['email'];
        $user->height = $input['height'];
        $user->weight = $input['weight'];
        $user->language = $input['language'];
        $user->additional_info = $input['additional_info'];
        $user->country_code = $input['country_code'];
        $user->mobile_number = preg_replace('`-`', '', $input['mobile_number']);
        $user->city = $input['city'];
        $user->state = $input['state'];
        $user->street = $input['street'];
        $user->dob = date("Y-m-d", strtotime($input['dob']));
        $user->gender = $input['gender'];
        $user->save();

        $caregiverid = DB::table('caregiver')->select('id')->where('user_id','=', $id)->first();
        $caregiver = Caregiver::findOrFail($caregiverid->id);
        $caregiver->service = '';
        $caregiver->min_price = $input['min_price'];
        $caregiver->max_price = $input['max_price'];
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
        $caregiver = Caregiver::where('user_id' , $id)->first();
        CaregiverAttribute::where('caregiver_id' , $id)->delete();
        AssignedCaregiver::where('caregiver_id' , $caregiver->id)->delete();
        ContactUs::where('user_id', $id)->delete();
        Message::where('user_id', $id)->delete();
        $caregiver->delete();

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

    public function getzip(Request $request){
        $city = $request->input('city');
        $state = $request->input('state');
        $zipcode = DB::select( DB::raw("SELECT zip FROM `us_location` where city = '".$city."' and state_code = '".$state."'"));
        echo $zipcode[0]->zip;
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
}
