<?php

namespace App\Http\Controllers;
use App\Booking;
use App\Caregiver;
use App\Diagnose;
use App\User;
use App\AssignedCaregiver;
use App\Countyareas;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use App\Us_location;
use App\Relation;
use App\Mail\MailHelper;
use Illuminate\Support\Facades\Mail;
use App\Helper;
use DB;

class BookingsController extends Controller{
    public function index(){
    	$bookings = Booking::orderBy('created_at', 'DESC')->get();
        $booking_type = Booking::select('booking_type')->distinct()->get()->toArray();
        $select_booking_type = '';

        //if filter by booking type
        if(!empty($_GET['booking_options'])){
            $if_in_array = in_array($_GET['booking_options'], array_column($booking_type, 'booking_type'));
            if($if_in_array) {
                $bookings = Booking::where('booking_type', '=', $_GET['booking_options'])->orderBy('created_at', 'DESC')->get();
                $select_booking_type = $_GET['booking_options'];
            } 
        }
        return view('bookings.index' , compact('bookings', 'booking_type', 'select_booking_type'));
    }

    public function show($id){
    	$booking = Booking::findOrFail($id);
        $booking->start_time = Carbon::parse($booking->start_time)->format('g:i A') ;
        $booking->end_time = Carbon::parse($booking->end_time)->format('g:i A') ;
    	$caregivers = User::select('users.*','caregiver.id as caregiverId')->join('caregiver','caregiver.user_id','users.id')->orderBy('users.name','asc')->get();

        $assigned_caregivers = AssignedCaregiver::where('booking_id',$id)->get();
        $assignedCaregivers = array();
        $assignedCaregiversId = array();
        foreach ($assigned_caregivers as $key => $value) {
            $assignedCaregiversId[] = $value->caregiver_id;
            $assignedCaregivers[$key]['name'] = $value->caregiver->user->name;
            $assignedCaregivers[$key]['email'] = $value->caregiver->user->email;
        }
    	foreach (unserialize($booking->diagnosis_id) as $key => $value) {
    		$diagnosis[] = Diagnose::select('title')->where('id', $value)->get()[0]->title;
    	}
    	$diagnosis = implode(',', $diagnosis);

    	return view('bookings.view' , compact('booking','caregivers','diagnosis','assignedCaregivers','assignedCaregiversId')); 

    }

    public function assign(Request $request){
        $input = $request->input();
        $exists = AssignedCaregiver::where('booking_id',$input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->get();
        if(count($exists) < 1){
            AssignedCaregiver::insert(['booking_id'=>$input['booking_id'],'caregiver_id'=> $input['caregiver_id']]);
            //Status Update
            Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Caregiver Request'));
            flash()->success("Caregiver assigned.");

            $booking = Booking::where('id', '=', $input['booking_id'])->with('user')->first();
            if($booking['user']['is_notify'] == 1)
                Helper::sendNotifications($booking['user']['id'], $booking->id, 'Caregiver Assigned', 'Caregivers has been assigned for booking. Please select a caregiver from caregiver request section.');
        }else{            
            AssignedCaregiver::where('booking_id',$input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->delete();
            flash()->success("Caregiver removed.");

            $isexist = AssignedCaregiver::where('booking_id',$input['booking_id'])->get();
            if(count($isexist) < 1){
                //change status 
                Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Pending'));
            }
        }
        return redirect()->back();
    }

    public function today_form($id){
        $booking = Booking::findOrFail($id);
        $booking->start_time = Carbon::parse($booking->start_time)->format('g:i A') ;
        $booking->end_time = Carbon::parse($booking->end_time)->format('g:i A') ;
        $serviceLocation = Countyareas::select('id','area')->where('area' , '!=' ,'0')->get()->toArray();
        return view('bookings.edit' , compact('booking','serviceLocation'));
    }

    public function today_update(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,[
            'todaystarttime' => 'required|string|max:40',
            'todayendtime' => 'required|string|max:40',
            'booking_id' => 'required|string|max:40',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'serviceLocation' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $booking = Booking::findOrFail($input['booking_id']);
        $booking->start_time = Carbon::parse($input['todaystarttime'])->format('H:i') ;
        $booking->end_time = Carbon::parse($input['todayendtime'])->format('H:i') ;
        $booking->address = $input['address'];
        $booking->city = $input['city'];
        $booking->state = $input['state'];
        $booking->country = $input['country'];
        $booking->zipcode = $input['zipcode'];
        $booking->service_location_id = $input['serviceLocation'];
        $booking->save();

        flash()->success('Booking Update Successfully');
        return redirect()->route('bookings.today_form', ['id' => $input['booking_id']]);
    }

    public function select_date_form($id)
    {
        $booking = Booking::where('id', '=', $id)->with('user')->with('relation')->with('service_location')->get()->first()->toArray();
        $booking['start_time'] = Carbon::parse($booking['start_time'])->format('g:i A') ;
        $booking['end_time'] = Carbon::parse($booking['end_time'])->format('g:i A') ;
        $relation = Relation::where('id' , $booking['relation']['relation_id'])->first();
        $relationname = $booking['relation_id'] == '' ? 'Myself' :  $booking['relation']['name'].'-'.$relation['title'] ;
        $serviceLocation = Countyareas::select('id','area')->where('area' , '!=' ,'0')->get()->toArray();

        return view('bookings.select_date_form' , compact('booking','relationname','serviceLocation'));
    }

    public function update_select_date_form(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,
            [
                'city' => 'required',
                'start_date'=>'required',
                'is_full_day' => 'required',
                'booking_id' => 'required|max:40',
                'address' => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'serviceLocation' =>'required'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }        
        $booking = array();
        $booking['start_date'] = Carbon::parse($input['start_date'])->format('m/d/Y');
        $booking['end_date'] = Carbon::parse($input['start_date'])->format('m/d/Y');
        $booking['start_time'] = Carbon::parse($input['todaystarttime'])->format('H:i') ;
        $booking['end_time'] = Carbon::parse($input['todayendtime'])->format('H:i') ;
        $booking['address'] = $input['address'];
        $booking['city'] = $input['city'];
        $booking['state'] = $input['state'];
        $booking['country'] = $input['country'];
        $booking['zipcode'] = $input['zipcode'];
        $booking['24_hours ']= $input['is_full_day'];
        $booking['service_location_id']= $input['serviceLocation'];
        if($input['is_full_day']){
            $booking['start_time'] = '00:00:00';
            $booking['end_time'] = '23:59:59';
        }
        $updatebooking = Booking::findOrFail($input['booking_id'])->update($booking);
 
        flash()->success('Booking Update Successfully');
        return redirect()->route('bookings.select_date_form', ['id' => $input['booking_id']]);
    }

    public function daily_form($id){
        $booking = Booking::where('id', '=', $id)->with('user')->with('relation')->with('service_location')->get()->first()->toArray();
        $booking['start_time'] = Carbon::parse($booking['start_time'])->format('g:i A') ;
        $booking['end_time'] = Carbon::parse($booking['end_time'])->format('g:i A') ;
        $relation = Relation::where('id' , $booking['relation']['relation_id'])->first();
        $relationname = $booking['relation_id'] == '' ? 'Myself' :  $booking['relation']['name'].'-'.$relation['title'] ;
        $serviceLocation = Countyareas::select('id','area')->where('area' , '!=' ,'0')->get()->toArray();

        return view('bookings.daily_form' , compact('booking','relationname','serviceLocation'));
    }

    public function update_daily_form(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,
            [
                'city' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'is_full_day' => 'required',
                'booking_id' => 'required|string|max:40',
                'address' => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'serviceLocation' => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $booking = Booking::findOrFail($input['booking_id']);
        $booking['start_date'] = Carbon::parse($input['start_date'])->format('m/d/Y');
        $booking['end_date'] = Carbon::parse($input['end_date'])->format('m/d/Y');
        $booking['start_time'] = Carbon::parse($input['todaystarttime'])->format('H:i') ;
        $booking['end_time'] = Carbon::parse($input['todayendtime'])->format('H:i') ;
        $booking['address'] = $input['address'];
        $booking['city'] = $input['city'];
        $booking['state'] = $input['state'];
        $booking['country'] = $input['country'];
        $booking['zipcode'] = $input['zipcode'];
        $booking['service_location_id'] = $input['serviceLocation'];
        $booking['24_hours'] = $input['is_full_day'];
        if($input['is_full_day']){
            $booking->start_time = '00:00:00';
            $booking->end_time = '23:59:59';
        }
        $booking->save();

        flash()->success('Booking Update Successfully');
        return redirect()->route('bookings.daily_form', ['id' => $input['booking_id']]);
    }

    public function select_from_week_form($id){
        $booking = Booking::findOrFail($id);
        $booking->start_time = Carbon::parse($booking->start_time)->format('g:i A') ;
        $booking->end_time = Carbon::parse($booking->end_time)->format('g:i A') ;
        $serviceLocation = Countyareas::select('id','area')->where('area' , '!=' ,'0')->get()->toArray();

        return view('bookings.select_from_week_form' , compact('booking','serviceLocation')); 
    }

    public function update_select_from_week_form(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,
            [
                'city' => 'required',
                'weekdays'=>'required',
                'no_of_weeks'=>'required',
                'is_full_day' => 'required',
                'booking_id' => 'required|string|max:40',
                'address' => 'required',
                'state' => 'required',
                'country' => 'required',
                'zipcode' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $startDate = Carbon::now()->format('m/d/Y');
        $endDate = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');

        $dates = Self::getDates($startDate , $endDate , $input['weekdays']);
        
        $booking = Booking::findOrFail($input['booking_id']);
        $booking['start_date'] = Carbon::parse($dates[0])->format('m/d/Y');
        $booking['end_date'] = Carbon::parse(end($dates))->format('m/d/Y');  
        $booking['start_time'] = Carbon::parse($input['todaystarttime'])->format('H:i') ;
        $booking['end_time'] = Carbon::parse($input['todayendtime'])->format('H:i') ;
        $booking['address'] = $input['address'];
        $booking['city'] = $input['city'];
        $booking['state'] = $input['state'];
        $booking['country'] = $input['country'];
        $booking['zipcode'] = $input['zipcode'];
        $booking['service_location_id'] = $input['serviceLocation'];
        $booking['24_hours'] = $input['is_full_day'];
        if($input['is_full_day']){
            $booking->start_time = '00:00:00';
            $booking->end_time = '23:59:59';
        }
        $booking['weekdays'] = serialize($input['weekdays']);
        $booking['no_of_weeks'] = $input['no_of_weeks'];
        $booking->save();

        flash()->success('Booking Update Successfully');
        return redirect()->route('bookings.select_from_week_form', ['id' => $input['booking_id']]);
    }

    public function searchcity($term){
        $search_zipx = array();

        $search_city = Us_location::select('city')->Where("city","like","{$term}%")->groupBy('city')->orderBy("city","asc")->get();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $booking = Booking::findOrFail($id);
        $assignedCaregivers = AssignedCaregiver::where('booking_id',$booking->id)->delete();

        if ($booking->delete()) {
            $response = array(
                'status' => 'success',
                'message' => 'Booking deleted successfully',
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Booking can not be deleted, Please try again',
            );
        }
        return json_encode($response);
    }

    public function complete_booking($id){
        $booking = Booking::findOrFail($id);
        if(empty($booking)){
            $response = array(
                'status' => 'success',
                'message' => 'Invalid Booking, Please try again',
            );    
        }

        $booking->status = 'Completed';
        if ($booking->save()) {
            $response = array(
                'status' => 'error',
                'message' => 'Booking mark as completed successfully',
            );    
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Booking can not be mark as completed, Please try again',
            );    
        }
        return json_encode($response);
    }

    public function getDates($startDate , $endDate ,$weekDays)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        
        $data = array();

        while($endDate->gte($startDate))
        {  
            if($weekDays == null){
                $data[]= $startDate->format('Y-m-d');
            }else if(in_array($startDate->format('D'), $weekDays)) {
                $data[]= $startDate->format('Y-m-d');
            }

            $startDate = $startDate->addDay(1);
        }
        return $data;
   }
   
   public function confirm_careservice($token){
        //need to start work on this...
        $isexist = DB::table('users')->where('carepack_mail_token', '=', $token)->first();
        $data = array();

        if($isexist){
            //show upload form
            $data['token'] = $isexist->carepack_mail_token;
        }else{
            //show page with error message
            $data['error'] = 'Oops, look like link is expire or invalid, please contact to 24*7 Nursing Care Admin';
        }
        return view('bookings.upload_carepack_docs', compact('data'));
    }

    public function upload_carepack_docs(Request $request){
        if($request->has('care_pack') && ($request->file('care_pack') != null)) {
            $input = $request->input();
            $isrequest = DB::table('users')->where('carepack_mail_token', '=', $input['token'])->first();
            if(empty($isrequest)){
                flash()->success("'Oops, look like link is expire or invalid, please contact to 24*7 Nursing Care Admin'");
                return view('bookings.upload_carepack_docs', compact('data'));
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
            $data = array('upload' => 'success', 'message' => 'Thanks for uploading the documents, Admin will contact you soon.');

            return view('bookings.upload_carepack_docs', compact('data'));
        }else{
            flash()->success("Please upload basic care pack document");
            return view('bookings.upload_carepack_docs', compact('data'));
        }
    }

    public function confirm_doc($id){
        $isrequest = DB::table('bookings')->where('id', '=', $id)->first();
        if(empty($isrequest)){
            flash()->success("Un-authorized Request");
            return redirect()->route('bookings.index');
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
        flash()->success("Uploaded Document approved.");
        return redirect()->route('bookings.show',['id' => $id]);
    }
}