<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Service_requests;
use App\Service_requests_attributes;
use App\User;
use App\RequestBooking;
use DB;
use App\Mail\MailHelper;
use Illuminate\Support\Facades\Mail;


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
        $caregiver_list = DB::table('users')->select('users.id', 'name', 'email', 'mobile_number', 'profile_image', 'users.is_blocked', 'users.created_at', 'gender')->Join('patients_profiles', 'patients_profiles.user_id', '=', 'users.id')->where('users.id','>', '1')->where('users.type', '=', 'patient')->orderBy('users.name', 'asc')->get();

        $service_list = DB::table('services')->orderBy('title', 'asc')->get();

        return view('service_request.create', compact('caregiver_list', 'service_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestr
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $input = $request->input(); 

        $validator =  Validator::make($input,[
            'user_id' => 'required|not_in:0',
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

        $service_request = array(
            'user_id' => $input['user_id'],
            'location' => $input['location'],
            'city' => $input['city'],
            'state' => $input['state'],
            'zip' => $input['zipcode'],
            'service' => $input['service'],
            'min_expected_bill' => $input['min_expected_bill'],
            'max_expected_bill' => $input['max_expected_bill'],
            'start_time' => $input['start_time'],
            'end_time' => $input['end_time'],
            'start_date' => date('Y-m-d', strtotime($input['start_date'])),
            'end_date' => date('Y-m-d', strtotime($input['end_date'])),
            'description' => $input['description'],
            'status' => $input['status'],
            'updated_at' => date('Y-m-d h:i:s')
        ); 
        DB::table('service_requests')->insert($service_request);

        flash()->success("Request created successfully.");
        return redirect()->route('service_request.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $services = DB::table('service_requests')->select('service_requests.id', 'service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();
        if(empty($services)){
            flash()->success("Request not Found"); 
            return redirect()->route('service_request.index');  
        }

        $final_caregivers =  DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'caregiver_list')->first();

        $upload_docs = DB::table('service_requests_attributes')->select('service_requests_attributes.*')->where('service_request_id', '=', $id)->where('type', '=', 'carepack_docs')->orderBy('id', 'desc')->get();
        return view('service_request.view', compact('services', 'final_caregivers', 'upload_docs'));
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

        flash()->success("Request detail updated successfully.");
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
            flash()->success("Invalid Request."); 
            return redirect()->route('service_request.index');  
        }

        $srvc->status = !$srvc->status;
        $srvc->save();
       
        if ($srvc->status)
            flash()->success("Request Reject successfully."); 
        else 
            flash()->success("Request Activate successfully."); 
        return redirect()->route('service_request.index');  
    }

    public function caregiver_list($id){
        $srvc = Service_requests::find($id);
        if(empty($srvc)){
            flash()->success("Invalid Request."); 
            return redirect()->route('service_request.index');  
        }
        
        $services = DB::table('service_requests')->select('service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'services.title','service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();

        //non filter caregivers list
        /*$caregivers = DB::table('users')->select('users.id', 'name', 'email', 'mobile_number', 'profile_image', 'users.is_blocked', 'users.created_at', 'services.title', 'min_price', 'max_price', 'gender', 'zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->leftJoin('services', 'services.id', '=', 'caregiver.service')->where('users.id','>', '1')->where('users.type', '=', 'caregiver')->orderBy('users.id', 'desc')->get();*/

        $caregivers = DB::table('users')->select('users.id', 'name', 'email', 'mobile_number', 'profile_image', 'users.is_blocked', 'users.created_at', 'min_price', 'max_price', 'gender', 'zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->Join('caregiver_attributes', 'caregiver_attributes.caregiver_id', '=', 'users.id')->where('users.id','>', '1')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.value', '=', $srvc->service)->orderBy('users.name', 'asc')->get();

        $final_caregivers = DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'caregiver_list')->get();

        $caregivers = DB::table('users')->select('users.id', 'name', 'email', 'mobile_number', 'profile_image', 'users.is_blocked', 'users.created_at', 'min_price', 'max_price', 'gender', 'zipcode')->Join('caregiver', 'caregiver.user_id', '=', 'users.id')->Join('caregiver_attributes', 'caregiver_attributes.caregiver_id', '=', 'users.id')->where('users.id','>', '1')->where('caregiver_attributes.type', '=', 'service')->where('caregiver_attributes.value', '=', $srvc->service)->orderBy('users.name', 'asc')->get();

        $query = "select users.id, name, email, mobile_number, profile_image, users.is_blocked, users.created_at, min_price, max_price, gender, zipcode from users join caregiver on caregiver.user_id = users.id join caregiver_attributes on caregiver_attributes.caregiver_id = users.id where users.id > 1 and caregiver_attributes.type = 'service' and caregiver_attributes.value = '".$srvc->service."' and NOT EXISTS (select request_booking.start_date from request_booking where caregiver_id = users.id and (request_booking.start_date >= '".$srvc->start_date."' and request_booking.start_date <= '".$srvc->end_date."') || (request_booking.end_date >= '".$srvc->start_date."' and request_booking.end_date <= '".$srvc->end_date."') limit 1) order by users.name asc";
        
        //$caregivers = DB::select($query);
        //exists(select * from request_booking where caregiver_id = users.id and (request_booking.start_date >= '".$srvc->start_date."' AND request_booking.start_date <= '".$srvc->start_date."') || (request_booking.end_date >= '".$srvc->start_date."' AND request_booking.end_date <= '".$srvc->start_date."'))

        /* echo "<pre>";
        print_r($services);
        print_r($caregivers);
        die; */

        $select_caregiver = array(0);
        foreach($final_caregivers as $scr){
            $select_caregiver[] = $scr->value;
        }

        $picked_caregiver = DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'final_caregiver')->first();

        $picked_cargiver_id = 0;
        $picked_caregiver = DB::table('service_requests_attributes')->where('service_request_id', '=', $id)->where('type', '=', 'final_caregiver')->first();
        if(!empty($picked_caregiver)){
            $picked_cargiver_id = $picked_caregiver->value;
        }

        $picked_caregiver = array();
        if($srvc->status > 4){
            $picked_caregiver = DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'final_caregiver')->first();
        }    
        
        $picked_cargiver_id = 0;
        $picked_caregivers = DB::table('service_requests_attributes')->where('service_request_id', '=', $id)->where('type', '=', 'final_caregiver')->first();
        if(!empty($picked_caregivers)){
            $picked_cargiver_id = $picked_caregivers->value;
        }
        
        return view('service_request.caregiverslist', compact('services', 'caregivers', 'select_caregiver', 'final_caregivers', 'picked_caregiver', 'picked_cargiver_id'));
    }

    public function assign(Request $request){
        $input = $request->input(); 
        $srvc = Service_requests::find($input['request_id']);
        if(empty($srvc)){
            flash()->success("Invalid Request."); 
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

    function picked_caregiver(Request $request){
        $input = $request->input();
        $srvc = Service_requests::find($input['request_id']);
        if(empty($srvc)){
            flash()->success("Invalid Request."); 
            return redirect()->route('service_request.index'); 
        }

        $crgvr = User::find($input['caregiver_id']);
        if(empty($crgvr)){
            flash()->success("Invalid Caregiver."); 
            return redirect()->route('service_request.caregiver_list',['id' => $input['request_id']]); 
        }

        $isexist = DB::table('service_requests_attributes')->where('service_request_id', '=', $input['request_id'])->where('type', '=', 'final_caregiver')->first();
        if(empty($isexist)){
            $request = array(
                'service_request_id' => $input['request_id'],
                'value' => $input['caregiver_id'],
                'type' => 'final_caregiver'
            ); 
            DB::table('service_requests_attributes')->insert($request);
        }
        $service_request = DB::table('service_requests_attributes')->where('service_request_id', '=', $input['request_id'])->where('type', '=', 'final_caregiver')->update(array('value' => $input['caregiver_id']));
        $service_request = DB::table('service_requests')->where('id', '=', $input['request_id'])->update(array('status' =>  '4'));

        flash()->success("Caregiver picked for request successfully."); 
        return redirect()->route('service_request.caregiver_list',['id' => $input['request_id']]); 
    }

    function confirm_caregiver(Request $request){
        $input = $request->input();

        $srvc = Service_requests::find($input['request_id']);
        if(empty($srvc)){
            flash()->success("Invalid Request."); 
            return redirect()->route('service_request.index'); 
        }

        $crgvr = User::find($input['caregiver_id']);
        if(empty($crgvr)){
            flash()->success("Invalid Caregiver."); 
            return redirect()->route('service_request.caregiver_list',['id' => $input['request_id']]); 
        }

        $patient = User::find($srvc->user_id);
        if(empty($srvc)){
            flash()->success("Invalid Patient."); 
            return redirect()->route('service_request.index'); 
        }

        $token = md5(uniqid(rand(), true));
        
        $service_request = DB::table('service_requests')->where('id', '=', $input['request_id'])->update(array('status' =>  '5', 'token' => $token));
        //$service_request = DB::table('service_requests')->where('id', '=', $input['request_id'])->update(array('token' => $token));
        
        $objDemo = new \stdClass();
        $objDemo->sender = env('APP_NAME');
        $objDemo->receiver = ucfirst($patient->name);
        $objDemo->type = 'basic_carepack_confirm';
        $objDemo->format = 'basic';
        $objDemo->subject = 'Basic Care Service Pack Mail';
        $objDemo->mail_from = env('MAIL_FROM_EMAIL');
        $objDemo->mail_from_name = env('MAIL_FROM_NAME');
        $objDemo->weburl = env('APP_URL')."confirm_careservice/".$token;
        //$patient->email = 'sonu.shokeen@saffrontech.net';
        $issemd = Mail::to($patient->email)->send(new MailHelper($objDemo));

        //redirect back to list page
        flash()->success("Basic Care Service Pack mail to Patient sent successfully."); 
        return redirect()->route('service_request.caregiver_list',['id' => $input['request_id']]); 
    }

    public function confirm_careservice($token){
        //need to start work on this...
        $isexist = DB::table('service_requests')->where('token', '=', $token)->where('status', '=', '5')->first();
        $data = array();

        if($isexist){
            //show upload form
            $data['token'] = $isexist->token;
        }else{
            //show page with error message
            $data['error'] = 'Oops, look like link is expire or invalid, please contact to 24*7 Nursing Care Admin';
        }
        return view('service_request.upload_carepack', compact('data'));
    }   

    public function upload_carepack_docs(Request $request){
        if($request->has('care_pack') && ($request->file('care_pack') != null)) {
            $input = $request->input();
            $isrequest = DB::table('service_requests')->where('token', '=', $input['token'])->first();
            if(empty($isrequest)){
                flash()->success("'Oops, look like link is expire or invalid, please contact to 24*7 Nursing Care Admin'"); 
                return view('service_request.upload_carepack', compact('data'));    
            }

            $docs = $request->file('care_pack');
            $token = md5(uniqid(rand(), true));
            $doc_name = $token.time().'.'.$docs->getClientOriginalExtension();

            $destinationPath = public_path('/request_docs');
            $docs->move($destinationPath, $doc_name);

            $request = array(
                'service_request_id' => $isrequest->id,
                'value' => $doc_name,
                'type' => 'carepack_docs'
            ); 
            DB::table('service_requests_attributes')->insert($request);
            $service_request = DB::table('service_requests')->where('token', '=', $input['token'])->update(array('status' => 6));
            $data = array('upload' => 'success', 'message' => 'Thanks for aupload Document, Admin will contact you soon.');

            return view('service_request.upload_carepack', compact('data'));
        }else{
            flash()->success("Please upload basic care pack document"); 
            return view('service_request.upload_carepack', compact('data'));    
        }
    }

    public function confirm_doc($id){
        $isrequest = DB::table('service_requests')->where('id', '=', $id)->first();
        if(empty($isrequest)){
            flash()->success("Un-authorized Request"); 
            return redirect()->route('service_request.index');
        }

        //get final confirmed caregiver
        $caregiver = DB::table('service_requests_attributes')->where('service_request_id', '=', $id)->where('type', '=', 'final_caregiver')->first();

        //save request booking
        $requestbooking = new \App\RequestBooking;
        $requestbooking->request_id = $id;
        $requestbooking->caregiver_id = $caregiver->value;
        $requestbooking->start_date = date('Y-m-d', strtotime($isrequest->start_date));
        $requestbooking->end_date = date('Y-m-d', strtotime($isrequest->end_date));
        $requestbooking->save();

        //redirect back to list page
        $service_request = DB::table('service_requests')->where('id', '=', $id)->update(array('status' => 7));

        flash()->success("Uploaded Document approved."); 
        return redirect()->route('service_request.show',['id' => $id]);
    }

    public function reschedule($id){
        $services = DB::table('service_requests')->select('service_requests.id', 'service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->where('service_requests.id', $id)->first();
        if(empty($services)){
            flash()->success("Request not Found"); 
            return redirect()->route('service_request.index');  
        }

        $final_caregivers = DB::table('service_requests_attributes')->select('service_requests_attributes.value', 'users.name', 'users.email')->Join('users', 'users.id', '=', 'service_requests_attributes.value')->where('service_request_id', '=', $id)->where('service_requests_attributes.type', '=', 'caregiver_list')->get();

        $upload_docs = DB::table('service_requests_attributes')->select('service_requests_attributes.*')->where('service_request_id', '=', $id)->where('type', '=', 'carepack_docs')->orderBy('id', 'desc')->get();
        return view('service_request.view', compact('services', 'final_caregivers', 'upload_docs'));
    }

    public function resendmail($id){
        $srvc = Service_requests::find($id);
        if(empty($srvc)){
            flash()->success("Invalid Request."); 
            return redirect()->route('service_request.index'); 
        }

        $patient = User::find($srvc->user_id);
        if(empty($patient)){
            flash()->success("Invalid Patient."); 
            return redirect()->route('service_request.index'); 
        }

        $token = md5(uniqid(rand(), true));
        
        $service_request = DB::table('service_requests')->where('id', '=', $id)->update(array('status' =>  '5', 'token' => $token));
        //$service_request = DB::table('service_requests')->where('id', '=', $id)->update(array('token' => $token));
        
        $objDemo = new \stdClass();
        $objDemo->sender = env('APP_NAME');
        $objDemo->receiver = ucfirst($patient->name);
        $objDemo->type = 'resend_basic_carepack_confirm';
        $objDemo->format = 'basic';
        $objDemo->subject = 'Basic Care Service Pack Mail';
        $objDemo->mail_from = env('MAIL_FROM_EMAIL');
        $objDemo->mail_from_name = env('MAIL_FROM_NAME');
        $objDemo->weburl = env('APP_URL')."confirm_careservice/".$token;
        //$patient->email = 'sonu.shokeen@saffrontech.net';
        $issemd = Mail::to($patient->email)->send(new MailHelper($objDemo));

        //redirect back to list page
        flash()->success("Basic Care Service Pack mail resend to Patient sent successfully."); 
        return redirect()->route('service_request.show',['id' => $id]); 
    }

    public function download_excel(){
        $services = DB::table('service_requests')->select('service_requests.id', 'service_requests.description', 'service_requests.created_at', 'service_requests.start_time', 'service_requests.end_time', 'service_requests.service', 'service_requests.id', 'service_requests.user_id', 'service_requests.location', 'service_requests.city', 'service_requests.state', 'service_requests.zip', 'service_requests.country', 'service_requests.min_expected_bill', 'service_requests.max_expected_bill', 'service_requests.start_date', 'service_requests.end_date', 'service_requests.status', 'users.name', 'users.email', 'users.mobile_number', 'users.name', 'users.name', 'users.is_blocked', 'services.title')->Join('users', 'service_requests.user_id', '=', 'users.id')->Join('services', 'services.id', '=', 'service_requests.service')->get();
        
        $filename = "Requests.xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        if(empty($services)){
            echo 'No records Found...';
        }{
            $isPrintHeader = false;
            $header = array(
                'S. No.', 
                'Patient Name', 
                'Caregiver Name', 
                'Street',
                'City',
                'State',
                'Country',
                'Pin Code',
                'Price Range',
                'Shift',
                'From',
                'To',
                'status',
                'Created On',
            );

            $count = 1;
            foreach ($services as $row) {
                if (!$isPrintHeader) {
                    echo implode("\t", array_values($header)) . "\n";
                    $isPrintHeader = true;
                }

                $caregivername = 'NA';
                if(!empty($final_caregivers)){
                    $caregivername = ucfirst($final_caregivers->name)." (".$final_caregivers->email.")<br/>";
                }

                $request_status = 'dddddddd';
                switch($row->status){
                    case '0':
                        $request_status = "Pending";
                        break;
                    case '1':
                        $request_status = "Reject";
                        break;    
                    case '2':
                        $request_status = "Approved";
                        break;    
                    case '3':
                        $request_status = "Caregiver not Assign";
                        break;
                    case '4':
                        $request_status = "Assign to Caregiver"; 
                        break;       
                    case '5':
                        $request_status = "Caregiver confirm and sent mail of basic careservice pack";
                        break;    
                    case '6':
                        $request_status = "Document upload by patient, but document not varified";
                        break;        
                    case '7':
                        $request_status = "Uploaded document varified";
                        break;            
                    case '8':
                        $request_status = "Re-schedule";
                    case '9':
                        $request_status = "Close";    
                        break;  
                }

                $range = "$".$row->min_expected_bill." to $".$row->max_expected_bill;
                $shift = substr_replace($row->start_time, ":", 2, 0)." to ".substr_replace($row->end_time,  ":", 2, 0);
                        
                $temp = array(
                    $count.".", 
                    ucfirst(str_replace(",", " ", $row->name)), 
                    $caregivername,
                    $row->location,
                    $row->city,
                    $row->state,
                    $row->country,
                    $row->zip,
                    $range,
                    $shift,
                    date_format(date_create($row->start_date), 'd M, Y'),
                    date_format(date_create($row->start_date), 'd M, Y'),
                    $request_status,
                    date_format(date_create($row->created_at), 'd M, Y')
                );
                echo implode("\t", array_values($temp)) . "\n";
                $count++;
            }
        }
        exit();        
    }
}