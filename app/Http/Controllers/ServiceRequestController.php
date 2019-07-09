<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Service_requests;
use App\Service_requests_attributes;
use DB;


class ServiceRequestController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */

    public function index(){
        $services = DB::table('service_requests')->select('service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->orderBy('service_requests.id', 'desc')->get();
        return view('service_request.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $services = DB::table('service_requests')->select('service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();
        if(empty($services)){
            flash()->success("Service Request not Found"); 
            return redirect()->route('service_request.index');  
        }

        $final_caregivers = DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'caregiver_list')->get();
        return view('service_request.view', compact('services', 'final_caregivers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $services = DB::table('service_requests')->select('service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();
        $services->caregiver_id = 'Not Assign';
        $service_list = DB::table('services')->orderBy('title', 'asc')->get();
        return view('service_request.edit', compact('services', 'service_list'));
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
            'service' => 'required|not_in:0',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'min_expected_bill' => 'required|min:0',
            'max_expected_bill' => 'required|min:1',
            'location' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        $service_request = Service_requests::findOrFail($id);
        $service_request->location = $input['location'];
        $service_request->city = $input['city'];
        $service_request->state = $input['state'];
        $service_request->zip = $input['zipcode'];
        $service_request->service = $input['service'];
        $service_request->min_expected_bill = $input['min_expected_bill'];
        $service_request->max_expected_bill = $input['max_expected_bill'];
        $service_request->start_time = $input['start_time'];
        $service_request->end_time = $input['end_time'];
        //$service_request->start_date = $input['start_date'];
        //$service_request->end_date = $input['end_date'];
        $service_request->description = $input['description'];
        $service_request->status = $input['status'];
        $service_request->updated_at = date('Y-m-d h:i:s');
        $service_request->save();

        flash()->success("Service Request detail updated successfully.");
        return redirect()->route('service_request.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function blocked($id){
        $srvc = Service_requests::find($id);
        if(empty($srvc)){
            flash()->success("Invalid Service Request."); 
            return redirect()->route('service_request.index');  
        }

        $srvc->status = !$srvc->status;
        $srvc->save();
       
        if ($srvc->status)
            flash()->success("Service Request Reject successfully."); 
        else 
            flash()->success("Service Request Activate successfully."); 
        return redirect()->route('service_request.index');  
    }

    public function caregiver_list($id){
        $srvc = Service_requests::find($id);
        if(empty($srvc)){
            flash()->success("Invalid Service Request."); 
            return redirect()->route('service_request.index');  
        }
        
        $services = DB::table('service_requests')->select('service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();

        //non filter caregivers list
        $caregivers = DB::table('users')->select('users.id', 'name', 'email', 'mobile_number', 'profile_image', 'is_blocked', 'users.created_at', 'services.title', 'min_price', 'max_price', 'gender', 'zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'caregiver.service')->where('users.id','>', '1')->where('users.type', '=', 1)->orderBy('users.id', 'desc')->get();

        $final_caregivers = DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'caregiver_list')->get();

        $select_caregiver = array(0);
        foreach($final_caregivers as $scr){

            $select_caregiver[] = $scr->value;
        }
        return view('service_request.caregiverslist', compact('services', 'caregivers', 'select_caregiver', 'final_caregivers'));
    }

    public function assign(Request $request){
        $input = $request->input(); 
        $srvc = Service_requests::find($input['request_id']);
        if(empty($srvc)){
            flash()->success("Invalid Service Request."); 
            return redirect()->route('service_request.index');  
        }

        $findcaregiver = DB::table('service_requests_attributes')->where('service_request_id', '=', $input['request_id'])->where('type', '=', 'caregiver_list')->where('value', '=', $input['caregiver_id'])->first();
        if (empty($findcaregiver)){
            //assign to caregiver
            $request = array(
                'service_request_id' => $input['request_id'],
                'value' => $input['caregiver_id'],
                'type' => 'caregiver_list'
            ); 
            DB::table('service_requests_attributes')->insert($request);

            //update status to 3
            $service_request = DB::table('service_requests')->where('id', '=', $input['request_id'])->update(array('status' =>  '3'));
            flash()->success("Caregiver Add into caregiver list"); 
        }else{
            //un-assign to caregiver
            DB::table('service_requests_attributes')->where('id', '=', $findcaregiver->id)->delete();
            flash()->error("Caregiver Remove into caregiver list"); 

            $isexist = DB::table('service_requests_attributes')->where('service_request_id', '=', $input['request_id'])->where('type', '=', 'caregiver_list')->get();
            if(empty($isexist)){
                //change status of request
                $service_request = DB::table('service_requests')->where('id', '=', $input['request_id'])->update(array('status' => '2'));
            }
        }     
        return redirect()->route('service_request.caregiver_list',['id' => $input['request_id']]); 
    }
}