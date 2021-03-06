<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Diagnose;
use App\Service;
use App\Countyareas;
use App\Booking;
use App\Qualification;
use App\Relation;
use Validator;
use DB;
use Carbon\Carbon;
use App\AssignedCaregiver;
use Log;
use App\Caregiver;
use App\Notification;
use App\Helper;
use App\UserRelation;
use App\Mail\MailHelper;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{	
	public $successStatus = 200;
    public $errorStatus = 400;
    /**
     * Booking API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function booking(Request $request){
        $input = $request->input();
        $input['start_time'] = Carbon::parse($input['start_time'])->format('H:i') ;
        $input['end_time'] = Carbon::parse($input['end_time'])->format('H:i') ;
        $user = Auth::user();
        $validator =  Validator::make($input,
            [
                'relation_id' => 'required|string',
                'booking_type' => 'required',
                'height' =>'required',
                'weight' =>'required',
                'pets' => 'required',
                'diagnosis_id' => 'required',
                'services_id' => 'required',
                'service_location_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required|after_or_equal:start_date',
                'weekdays' => 'array',
                'weekdays.0' => 'sometimes|required',
                '24_hours' => 'required',
                'start_time' => 'required',
                'end_time' =>'required',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
                'timezone' => 'required'
            ],[
            	'weekdays.0.required' => 'Weekdays is required.'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        }
        if($input['relation_id'] == 'Myself' )
            $input['relation_id'] = null;

        $result = Self::validateBooking($input['start_date'], $input['end_date'], $input['start_time'], $input['end_time'], $input['booking_type'], $input['relation_id'], Auth::id(), $input['weekdays'], 'add', null);

        if($result['status'] == 'true'){
            return response()->json(['status_code' => 201 , 'message' => 'You already have a schedule at the specified time. Do you want to override it?', 'data' => ["override_id" => $result['id']]]);
        }

        if(($input['booking_type'] == 'Daily') || ($input['booking_type'] == 'Select date') || ($input['booking_type'] == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($input['booking_type'] == 'Select from week'){

            $dates = Self::getDates($input['start_date'] , $input['end_date'] , $input['weekdays']);
            
            if(empty($dates)){
                return response()->json(['status_code'=> 400, 'message'=> 'Please select valid date range.', 'data' => null]);
            }
        }

        foreach ($input['diagnosis_id'] as $key => $value) {
            $input['diagnosis'][] = Diagnose::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        foreach ($input['services_id'] as $key => $value) {
            $input['services'][] = Service::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['zipcode'] = $input['zip_code'];
        $input['user_id'] = $user->id;
        $input['weekdays'] = serialize($input['weekdays']);
        $input['diagnosis_id'] = serialize($input['diagnosis']);
        $input['services_id'] = serialize($input['services']);
        $input['status'] = 'Pending';
        $booking = Booking::create($input);

        if($booking){

            Helper::sendNotifications('1', $booking->id, 'New Schedule Request', 'New Schedule Request');

            if($user->is_notify == 1)
                Helper::sendNotifications(Auth::id(), $booking->id, 'Schedule Requested', 'Your schedule request has been generated.');

            Helper::sendTwilioMessage(Auth::user()->mobile_number, Auth::user()->country_code, 'A new schedule request has been confirmed for '.$booking->start_date.' at '.Carbon::parse($booking->start_time)->format('g:i A') .'. Your Schedule Id is NUR'.$booking->id); 

            if($booking->relation_id != null){
                Helper::sendTwilioMessage($booking->relation->mobile_number, Auth::user()->country_code, 'A new schedule request has been generated for you by '.Auth::user()->f_name.' for '.$booking->start_date.' at '.Carbon::parse($booking->start_time)->format('g:i A').'. Your Schedule Id is NUR'.$booking->id); 
            }
            
            $numbers = ['+13055251495','+17862478888','+17863995955'];
            Helper::sendContactUsMsg($numbers,'A new schedule request has been generated by '.$user->f_name.' '.$user->m_name.' '.$user->l_name);

            Self::sendConfirmationMail($user->id);
            Self::sendMailToClients($user, $booking);
            
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Schedule created successfully.', 'data' => null]);
        
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Schedule not created successfully.', 'data' => null]);
        }
    }

    public function validateBooking($startDate , $endDate , $startTime , $endTime, $bookingType, $relationId, $id, $weekDays, $type, $bookingId)
    {
        $result['status']=null;
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        if($type == 'add')
            $bookings = Booking::where('relation_id' , $relationId)->where('user_id', $id)->get()->toArray();
        else
            $bookings = Booking::where('relation_id' , $relationId)->where('user_id', $id)->where('id','!=',$bookingId)->get()->toArray();

        if($bookingType == 'Select date'){
            
            foreach ($bookings as $key => $booking) {
            $booking_startDate = Carbon::parse($booking['start_date']);
            $booking_endDate = Carbon::parse($booking['end_date']);

                if(($startDate->gte($booking_startDate) && $startDate->lte($booking_endDate))||($endDate->gte($booking_startDate) && $endDate->lte($booking_endDate))) {

                    if (($startTime >= $booking['start_time'] && $startTime <= $booking['end_time'])||($endTime >= $booking['start_time'] && $endTime <= $booking['end_time'])) 
                    {
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }

                    if($booking['24_hours'] == 1)
                    {
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }
                }
            }
        }else if($bookingType == 'Daily'){
            foreach ($bookings as $key => $booking) {
            $booking_startDate = Carbon::parse($booking['start_date']);
            $booking_endDate = Carbon::parse($booking['end_date']);

                if(($startDate->gte($booking_startDate) && $startDate->lte($booking_endDate))||($endDate->gte($booking_startDate) && $endDate->lte($booking_endDate))){

                    if (($startTime >= $booking['start_time'] && $startTime <= $booking['end_time'])||($endTime >= $booking['start_time'] && $endTime <= $booking['end_time'])) 
                    {
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }

                    if($booking['24_hours'] == 1)
                    {
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }
                }else if(($booking_startDate->gte($startDate) && $booking_startDate->lte($endDate))||($booking_endDate->gte($startDate) && $booking_endDate->lte($endDate))){

                    if (($startTime >= $booking['start_time'] && $startTime <= $booking['end_time'])||($endTime >= $booking['start_time'] && $endTime <= $booking['end_time'])) 
                    {
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }  

                    if($booking['24_hours'] == 1)
                    {
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }                   
                }
            }
        }else if($bookingType == 'Select from week'){
            foreach ($bookings as $key => $booking) {
            $booking_startDate = Carbon::parse($booking['start_date']);
            $booking_endDate = Carbon::parse($booking['end_date']);

            $dates = Self::getDates($startDate , $endDate , $weekDays);

                while($endDate->gte($startDate))
                {  
                    if(in_array($booking_startDate->format('Y-m-d'), $dates) || in_array($booking_endDate->format('Y-m-d'), $dates)){
                        $result['id'] = $booking['id'];
                        $result['status'] = true;
                    }
                    $startDate = $startDate->addDay(1);
                }
            }
        }
        $result['status']==null ? false :true;
        return $result;
    }

    /**
     * Booking API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit_booking(Request $request){
        $input = $request->input();
        $user = Auth::user();
        $input['start_time'] = Carbon::parse($input['start_time'])->format('H:i') ;
        $input['end_time'] = Carbon::parse($input['end_time'])->format('H:i') ;
        $booking = Booking::where('id', $input['booking_id'])->first();
        $validator =  Validator::make($input,
            [
                'booking_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required|after_or_equal:start_date',
                'weekdays' => 'array',
                '24_hours' => 'required',
                'service_location_id' => 'required',
                'start_time' => 'required',
                'end_time' =>'required',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
            ]
        );

        if($booking->booking_type == 'Select from week' )
            $validator = Validator::make($input, ['weekdays.0' => 'required'],['weekdays.0.required' => 'Weekdays is required.']);

        if ($validator->fails()) {
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);
        }

        $result = Self::validateBooking($input['start_date'], $input['end_date'], $input['start_time'], $input['end_time'], $booking->booking_type, $booking->relation_id, Auth::id(), $input['weekdays'], 'edit', $booking->id);

        if($result['status'] == 'true'){
            return response()->json(['status_code' => 201 , 'message' => 'You already have a schedule at the specified time. Do you want to override it?', 'data' => ["override_id" => $result['id']]]);
        }

        if(($booking->booking_type == 'Daily') || ($booking->booking_type == 'Select date') || ($booking->booking_type == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($booking->booking_type == 'Select from week'){
            /*$startDate = Carbon::now()->format('m/d/Y');
            $endDate = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');*/

            $dates = Self::getDates($input['start_date'] , $input['end_date'] , $input['weekdays']);
            
            if(empty($dates)){
                return response()->json(['status_code'=> 400, 'message'=> 'Please select valid date range.', 'data' => null]);
            }
            /*$input['start_date'] = Carbon::parse($dates[0])->format('m/d/Y');
            $input['end_date'] = Carbon::parse(end($dates))->format('m/d/Y');*/
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['zipcode'] = $input['zip_code'];
        $input['weekdays'] = serialize($input['weekdays']);
        $booking->fill($input);

        if($booking->save()){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Schedule updated successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Schedule not updated successfully.', 'data' => null]);
        }
    }

    /**
     * Override API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function override_booking(Request $request){
        $input = $request->input();
        $user = Auth::user();
        $input['start_time'] = Carbon::parse($input['start_time'])->format('H:i') ;
        $input['end_time'] = Carbon::parse($input['end_time'])->format('H:i') ;
        $booking = Booking::where('id', $input['override_id'])->first();
        $validator =  Validator::make($input,
            [
                'relation_id' => 'required|string',
                'booking_type' => 'required',
                'height' =>'required',
                'weight' =>'required',
                'pets' => 'required',
                'diagnosis_id' => 'required',
                'services_id' => 'required',
                'service_location_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'weekdays' => 'array',
                'weekdays.0' => 'sometimes|required',
                '24_hours' => 'required',
                'start_time' => 'required',
                'end_time' =>'required',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
                'timezone' => 'required',
                'override_id' =>'required',
            ]
        );

       if($input['booking_type'] == 'Select from week' )
            $validator = Validator::make($input, ['weekdays.0' => 'required'],['weekdays.0.required' => 'Weekdays is required.']);

        if ($validator->fails()) {
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);
        }

        if($input['relation_id'] == 'Myself' )
            $input['relation_id'] = null;

        if(($input['booking_type'] == 'Daily') || ($input['booking_type'] == 'Select date') || ($input['booking_type'] == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($input['booking_type'] == 'Select from week'){
            /*$startDate = Carbon::now()->format('m/d/Y');
            $endDate = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');*/

            $dates = Self::getDates($input['start_date'] , $input['end_date'] , $input['weekdays']);
            
            if(empty($dates)){
                return response()->json(['status_code'=> 400, 'message'=> 'Please select valid date range.', 'data' => null]);
            }
            /*$input['start_date'] = Carbon::parse($dates[0])->format('m/d/Y');
            $input['end_date'] = Carbon::parse(end($dates))->format('m/d/Y');*/
        }

        foreach ($input['diagnosis_id'] as $key => $value) {
            $input['diagnosis'][] = Diagnose::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        foreach ($input['services_id'] as $key => $value) {
            $input['services'][] = Service::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['zipcode'] = $input['zip_code'];
        $input['user_id'] = $user->id;
        $input['weekdays'] = serialize($input['weekdays']);
        $input['diagnosis_id'] = serialize($input['diagnosis']);
        $input['services_id'] = serialize($input['services']);
        $input['status'] = 'Pending';
        $booking->fill($input);

        if($booking->save()){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Schedule updated successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Schedule not updated successfully.', 'data' => null]);
        }
    }

    /**
     * Delete Booking API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_booking(Request $request){
        $input = $request->input();
        $user = Auth::user();
        $booking = Booking::where('id', $input['booking_id'])->delete();
        $assignedCaregivers = AssignedCaregiver::where('booking_id',$input['booking_id'])->delete();

        if($booking){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Schedule deleted successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Schedule not deleted successfully.', 'data' => null]);
        }
    }


    /**
     * MyBookings API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function my_bookings(){
        $user = Auth::user();

        $bookings = Booking::where('user_id' , $user->id)->with('relation')->with('service_location')->get()->toArray();
         
        foreach ($bookings as $key => $value) {
            if($value['relation_id'] != null){
                $relation = Relation::where('id' , $value['relation']['relation_id'])->first();
                $bookings[$key]['booking_for'] = $value['relation']['name'] .' - '. $relation['title'];
            }else{
                $bookings[$key]['booking_for'] = 'Myself';
            }

            $bookings[$key]['service_location_id'] = $value['service_location']['area'];

            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;

                $bookings[$key]['dates'] = Self::getDates($value['start_date'] , $value['end_date'] , unserialize($value['weekdays']));
            }

            if($value['booking_type'] == 'Daily'){
                $bookings[$key]['dates'] = Self::getDates($value['start_date'] , $value['end_date'] , null);
            }

            $bookings[$key]['start_time'] = Carbon::parse($value['start_time'])->format('g:i A') ;
            $bookings[$key]['end_time'] = Carbon::parse($value['end_time'])->format('g:i A') ;
            $bookings[$key]['bookingId'] = 'NUR'.$value['id'] ;

            if($value['diagnosis_id'] != null){
                $diagnosis = unserialize($value['diagnosis_id']);
                foreach ($diagnosis as $a => $v) {
                    $diagnose[$a]= Diagnose::select('title')->where('id', $v)->get()->toArray()[0]['title'];
                }
                $bookings[$key]['diagnosis_id'] = $diagnose;
            }

            if($value['services_id'] != null){
                $services = unserialize($value['services_id']);
                if($services != false){
                    foreach ($services as $a => $val) {
                        $service[$a]= Service::select('title')->where('id', $val)->get()->toArray()[0]['title'];
                    }
                    $bookings[$key]['services_id'] = $service;
                } 
            }               
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => '', 'data' => null]);
        }

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

    public function caregiverRequestsList()
    {
        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays', 'caregiver_limit')->where('status', 'Caregiver Request')->where('user_id' , Auth::id())->get();

        foreach ($bookings as $key => $value) {

            if($value['relation_id'] != null){
                $relation = Relation::where('id' , $value->relation->relation_id)->first();
                $value['booking_for'] = $value->relation->name .' - '. $relation['title'];
            }else{
                $value['booking_for'] = 'Myself';
            }
            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;
            }

            $bookings[$key]['start_time'] = Carbon::parse($value['start_time'])->format('g:i A') ;
            $bookings[$key]['end_time'] = Carbon::parse($value['end_time'])->format('g:i A') ;
            $bookings[$key]['bookingId'] = 'NUR'.$value['id'] ;

            foreach ($value->caregivers as $k => $care) {
                $bookings[$key]['caregivers'][$k]['name'] = $care->caregiver->user->f_name.' '.$care->caregiver->user->m_name.' '.$care->caregiver->user->l_name;
                if($care->caregiver->user->profile_image == null || empty($care->caregiver->user->profile_image))
                    $bookings[$key]['caregivers'][$k]['profile_image'] = 'default.png';
                else
                    $bookings[$key]['caregivers'][$k]['profile_image'] = $care->caregiver->user->profile_image;
            
                $bookings[$key]['caregivers'][$k]['language'] = unserialize($care->caregiver->user->language);
                $bookings[$key]['caregivers'][$k]['description'] = $care->caregiver->user->additional_info;
                $bookings[$key]['caregivers'][$k]['discipline'] = Qualification::select('name')->join('caregiver_attributes' ,'caregiver_attributes.value' , 'qualifications.id')->where('type' , 'qualification')->where('caregiver_id', $care->caregiver->user->id)->get()->toArray();
                $bookings[$key]['caregivers'][$k]['service_in'] = DB::table('caregiver_attributes')->select('county_areas.id','county_areas.area')->join('county_areas', 'county_areas.id','caregiver_attributes.value')->where('caregiver_id', '=', $care->caregiver->user->id)->where('type', '=', 'service_area')->get();
                $bookings[$key]['caregivers'][$k]['gender'] = $care->caregiver->user->gender;
            }
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => '', 'data' => null]);
        }
    }

    public function request_for_booking(Request $request)
    {
        $user = Auth::user();
        $input = $request->input();

        foreach ($input['caregiver_id'] as $key => $value) {
           $assign = AssignedCaregiver::where('booking_id' , $input['booking_id'])->where('caregiver_id', $value)->update(array('status' => 'Final'));
           $caregivers[] = Caregiver::where('id', $value)->first();
        }
        foreach ($caregivers as $key => $value) {
            $caregiverNames[] = $value['user']['f_name'].' '.$value['user']['l_name'];
        }
        $caregiverNames = implode(', ', $caregiverNames);
        
        //Status Update
        Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Upcoming'));

        if($assign){
            if($user->is_notify == 1)
                Helper::sendNotifications($user->id, $input['booking_id'], 'Booking Confirmed', $caregiverNames.' has been assigned for schedule.');
                
            $booking = Booking::where('id', '=', $input['booking_id'])->get();
            \Log::info($booking[0]->relation_id);
            if($booking[0]->relation_id != null){
                $bookingFor = UserRelation::findOrFail($booking[0]->relation_id)->name ;
            }else{
                $bookingFor = $user->f_name.' '.$user->m_name.' '.$user->l_name;
            }
                
            foreach ($caregivers as $key => $value) {
                if($value['user']['is_notify'] == 1)
                    Helper::sendNotifications($value['user']['id'], $input['booking_id'], 'Booking Scheduled', 'A new shift has been scheduled for '.$bookingFor.'.');
            }
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Request sent successfully.', 'data' => '']);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Request not sent successfully.', 'data' => null]);
        }
    }

    public function pending_bookings(Request $request){

        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays')->where('status', 'Pending')->where('user_id' , Auth::id())->get();

        foreach ($bookings as $key => $value) {

            if($value['relation_id'] != null){
                $relation = Relation::where('id' , $value->relation->relation_id)->first();
                $value['booking_for'] = $value->relation->name .' - '. $relation['title'];
            }else{
                $value['booking_for'] = 'Myself';
            }
            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;
            }

            $bookings[$key]['bookingId'] = 'NUR'.$value->id ;
            $bookings[$key]['start_time'] = Carbon::parse($value['start_time'])->format('g:i A') ;
            $bookings[$key]['end_time'] = Carbon::parse($value['end_time'])->format('g:i A') ;
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Schedule', 'data' => null]);
        }
    }

    public function upcoming_bookings(Request $request){

        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays')->where('status', 'Upcoming')->where('user_id' , Auth::id())->get();
        
        foreach ($bookings as $key => $value) {

            if($value['relation_id'] != null){
                $relation = Relation::where('id' , $value->relation->relation_id)->first();
                $value['booking_for'] = $value->relation->name .' - '. $relation['title'];
            }else{
                $value['booking_for'] = 'Myself';
            }
            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;
            }
            $bookings[$key]['bookingId'] = 'NUR'.$value->id ;
            $bookings[$key]['start_time'] = Carbon::parse($value->start_time)->format('g:i A') ;
            $bookings[$key]['end_time'] = Carbon::parse($value->end_time)->format('g:i A') ;
            $userCaregiver = array();
            $assignedCaregiver = AssignedCaregiver::where('booking_id', $value->id)->where('status', 'Final')->get();
            foreach ($assignedCaregiver as $k => $ac) {
                $datas['name'] = $ac->caregiver->user->f_name.' '.$ac->caregiver->user->m_name.' '.$ac->caregiver->user->l_name;
                $datas['profile_image'] = $ac->caregiver->user->profile_image == null ? 'default.png' : $ac->caregiver->user->profile_image ;
                $datas['language'] = unserialize($ac->caregiver->user->language);
                $datas['description'] = $ac->caregiver->user->description;
                $datas['discipline'] = Qualification::select('name')->join('caregiver_attributes' ,'caregiver_attributes.value' , 'qualifications.id')->where('type' , 'qualification')->where('caregiver_id', $ac->caregiver->user->id)->get()->toArray();
                $datas['start_time'] = $ac->start_time;
                $datas['end_time'] = $ac->end_time;
                $datas['start_date'] = $ac->start_date;
                $datas['end_date'] = $ac->end_date;
                $datas['gender'] = $ac->caregiver->user->gender;
                $datas['service_in'] = DB::table('caregiver_attributes')->select('county_areas.id','county_areas.area')->join('county_areas', 'county_areas.id','caregiver_attributes.value')->where('caregiver_id', '=', $ac->caregiver->user->id)->where('type', '=', 'service_area')->get();
                $userCaregiver[] = $datas;
            }
            $bookings[$key]['userCaregiver'] = $userCaregiver;
        } 

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Schedule', 'data' => null]);
        }
    }

    public function completed_bookings(Request $request){

        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays','caregiver_id')->where('status', 'Completed')->where('user_id' , Auth::id())->get();
    
        foreach ($bookings as $key => $value) {

            if($value['relation_id'] != null){
                $relation = Relation::where('id' , $value->relation->relation_id)->first();
                $value['booking_for'] = $value->relation->name .' - '. $relation['title'];
            }else{
                $value['booking_for'] = 'Myself';
            }
            if($value['weekdays'] != null){
                $data = unserialize($value['weekdays']);
                $bookings[$key]['weekdays'] = $data;
            }
            $bookings[$key]['bookingId'] = 'NUR'.$value->id ;
            $bookings[$key]['start_time'] = Carbon::parse($value->start_time)->format('g:i A') ;
            $bookings[$key]['end_time'] = Carbon::parse($value->end_time)->format('g:i A') ;
            $userCaregiver = array();
            $assignedCaregiver = AssignedCaregiver::where('booking_id', $value->id)->where('status', 'Final')->get();
            foreach ($assignedCaregiver as $k => $ac) {
                $datas['name'] = $ac->caregiver->user->f_name.' '.$ac->caregiver->user->m_name.' '.$ac->caregiver->user->l_name;
                $datas['profile_image'] = $ac->caregiver->user->profile_image == null ? 'default.png' : $ac->caregiver->user->profile_image ;
                $datas['language'] = unserialize($ac->caregiver->user->language);
                $datas['description'] = $ac->caregiver->user->description;
                $datas['discipline'] = Qualification::select('name')->join('caregiver_attributes' ,'caregiver_attributes.value' , 'qualifications.id')->where('type' , 'qualification')->where('caregiver_id', $ac->caregiver->user->id)->get()->toArray();
                $datas['start_time'] = $ac->start_time;
                $datas['end_time'] = $ac->end_time;
                $datas['start_date'] = $ac->start_date;
                $datas['end_date'] = $ac->end_date;
                $datas['gender'] = $ac->caregiver->user->gender;
                $datas['service_in'] = DB::table('caregiver_attributes')->select('county_areas.id','county_areas.area')->join('county_areas', 'county_areas.id','caregiver_attributes.value')->where('caregiver_id', '=', $ac->caregiver->user->id)->where('type', '=', 'service_area')->get();
                $userCaregiver[] = $datas;
            }
            $bookings[$key]['userCaregiver'] = $userCaregiver;
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Schedule', 'data' => null]);
        }
    }

    public function upcoming_bookings_caregiver (Request $request , $start_date = null, $end_date = null)
    { 
        $caregiver = Caregiver::select('id')->where('user_id',Auth::id())->first();
        $jobs = AssignedCaregiver::select('assigned_caregivers.*')->where('assigned_caregivers.caregiver_id', $caregiver['id'])->where('assigned_caregivers.status', 'Final')->where('assigned_caregivers.start_date' ,'!=', null)->where('b.status','Upcoming')->join('bookings as b','b.id','assigned_caregivers.booking_id')->addSelect('assigned_caregivers.start_time as shift_start_time', 'assigned_caregivers.end_time as shift_end_time','assigned_caregivers.start_date as shift_start_date','assigned_caregivers.end_date as shift_end_date')->get();

        foreach ($jobs as $key => $value) {
            
            if(($request->input('start_date') != "null") && ($request->input('end_date') != "null")){
                $bookingStart = Carbon::parse($value->booking->start_date);
                $bookingEnd = Carbon::parse($value->booking->end_date);
                $endDate = Carbon::parse($request->input('end_date'));
                $startDate = Carbon::parse($request->input('start_date'));
                    if(($bookingStart->gte($startDate) && $bookingStart->lte($endDate)) || ($bookingStart->lte($startDate)&&($bookingEnd->gte($startDate) && $bookingEnd->lte($endDate))) || ($bookingEnd->gte($endDate)&&($bookingStart->gte($startDate) && $bookingStart->lte($endDate))))
                    { 
                        $bookings[] = $value->booking;
                    }else{
                        return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Schedule', 'data' => null]);
                    }
            }else{
                $bookings[] = $value->booking;
            }
                if($value->booking->weekdays != null){
                    $data = unserialize($value->booking->weekdays);
                    $bookings[$key]['weekdays'] = $data;
                }
                $bookings[$key]['bookingId'] = 'NUR'.$value->booking_id ;
                $bookings[$key]['start_time'] = Carbon::parse($value->booking->start_time)->format('g:i A') ;
                $bookings[$key]['end_time'] = Carbon::parse($value->booking->end_time)->format('g:i A') ;
                $bookings[$key]['service_location_id'] = $value->booking->service_location->area;
                $bookings[$key]['user']['name'] = $value->booking->user->f_name.' '.$value->booking->user->m_name.' '.$value->booking->user->l_name;
                $bookings[$key]['user']['profile_image'] = $value->booking->user->profile_image == null ? 'default.png' : $value->booking->user->profile_image ;
                $bookings[$key]['user']['language'] = unserialize($value->booking->user->language);
                $bookings[$key]['user']['description'] = $value->booking->user->description;
                $bookings[$key]['shift_start_time'] = $value->shift_start_time ;
                $bookings[$key]['shift_end_time'] = $value->shift_end_time ;
                $bookings[$key]['shift_start_date'] = $value->start_date ;
                $bookings[$key]['shift_end_date'] = $value->end_date;
                $bookings[$key]['relation_id'] = $bookings[$key]['relation_id'] != null? UserRelation::findOrFail($bookings[$key]['relation_id'])->name : '';
        }

        if(count($jobs) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Schedule', 'data' => null]);
        }
    }

    public function complete_booking(Request $request){

        $input = $request->input();
        $validator =  Validator::make($input,
            [
                'booking_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status_code'=> $this->errorStatus, 'message'=> $validator->errors()->first(), 'data' => null]);
        }
        //Status Update
        $completed = Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Completed'));
        $booking = Booking::where('id', '=', $input['booking_id'])->first();

        if($completed){
            if($booking['user']['is_notify'] == 1)
                Helper::sendNotifications($booking['user']['id'], $booking->id, 'Booking Completed', 'Your schedule has been completed.');
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Schedule Completed successfully.', 'data' => '']);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Schedule not completed successfully.', 'data' => null]);
        }
    }

    public function completed_bookings_caregiver (Request $request)
    { 
        $caregiver = Caregiver::select('id')->where('user_id',Auth::id())->first();
        $assignedCaregivers = AssignedCaregiver::where('caregiver_id', $caregiver->id)->pluck('booking_id')->toArray();
        $bookings = Booking::select('id','user_id', 'start_date', 'end_date','start_time','end_time', 'booking_type', '24_hours','caregiver_id')->whereIn('id', $assignedCaregivers)->where('status', 'Completed')->get();

        foreach ($bookings as $key => $value) {
            $bookings[$key]['bookingId'] = 'NUR'.$value->id ;
            $bookings[$key]['user']['name'] = $value->user->f_name.' '.$value->user->m_name.' '.$value->user->l_name;
            $bookings[$key]['user']['profile_image'] = $value->user->profile_image == null ? 'default.png' : $value->user->profile_image ;
            $bookings[$key]['start_time'] = Carbon::parse($value->start_time)->format('g:i A') ;
            $bookings[$key]['end_time'] = Carbon::parse($value->end_time)->format('g:i A') ;
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Schedule', 'data' => null]);
        }
    }

    public function getNotifications(Request $request)
    {   
        $notifications = Notification::where('user_id', Auth::id())->whereDate('created_at', '>', Carbon::now()->subDays(30))->orderBy('created_at', 'DESC')->get()->toArray();

        Notification::where('user_id', Auth::id())->update(array('is_read' =>  '1'));

        if(count($notifications) > 0){
            foreach ($notifications as $key => $value) {
                $notifications[$key]['created_at'] = Carbon::parse($value['created_at'])->format('m/d/Y g:i A');
                $notifications[$key]['bookingId'] = 'NUR'.$value['booking_id'] ;
            }
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $notifications]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Notifications', 'data' => null]);
        }
    }

    public function unreadCount(Request $request){

        $unreadNotifcatons = Notification::where('is_read','0')->where('user_id' , Auth::id())->get();
        if($unreadNotifcatons){
            return response()->json(['status_code' => $this->successStatus , 'message' => '','data' => ['unreadNotifications' => count($unreadNotifcatons)]]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => '', 'data' => null]);
        }
    }

    public function sendConfirmationMail($id){
        $patient = User::find($id);
        if(empty($patient)){
            flash()->success("Invalid Client.");
            return false;
        }

        $token = md5(uniqid(rand(), true));
        $objDemo = new \stdClass();
        $objDemo->sender = env('APP_NAME');
        $objDemo->receiver = ucfirst($patient->f_name);
        $objDemo->type = 'basic_carepack_confirm';
        $objDemo->format = 'basic';
        $objDemo->subject = 'Basic Care Service Pack Mail';
        $objDemo->mail_from = env('MAIL_FROM_EMAIL');
        $objDemo->mail_from_name = env('MAIL_FROM_NAME');
        $objDemo->weburl = env('APP_URL')."confirm_careservice/".$token;
        $issend = Mail::to($patient->email)->send(new MailHelper($objDemo));
        if($issend == null){
            $user=User::where('id','=', $id)->update(['carepack_mail_token'=>$token]);
        }
        return true;
    }

       /**
     * Add Health Conditions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addHealthConditions(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:50',
        ]);

        if ($validator->fails())
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input = $request->input(); 
        $diagnosis = Diagnose::where('title', 'like', '%'.$input['title'].'%')->get();

        if(count($diagnosis) > 0){
            return response()->json(['status_code'=> 400, 'message'=> 'This Health Condition is already exists.', 'data' => null]);
        }else{

            $diagnose = Diagnose::create($input);
            $success['diagnosis'] = Diagnose::select('id', 'title')->where('is_blocked',0)->orderBy('title', 'asc')->get();

            return response()->json(['status_code' => $this->successStatus , 'message' => 'Health Condition added successfully. ', 'data' => $success]);
        }
    }
    
    public function sendMailToClients($user, $booking){
        $patient = User::find($user->id);
        if(empty($patient)){
            flash()->success("Invalid Client.");
            return false;
        }
        $emails = array("lmejer@24-7nursingcare.com", "fhernandez@24-7nursingcare.com", "mgomez@24-7nursingcare.com");

        $objDemo = new \stdClass();
        $objDemo->sender = env('APP_NAME');
        $objDemo->receiver = ucfirst('Admin');
        $objDemo->type = 'schedule_confirm_mail';
        $objDemo->subject = 'New Schedule Request';
        $objDemo->mail_from = env('MAIL_FROM_EMAIL');
        $objDemo->mail_from_name = env('MAIL_FROM_NAME');
        $objDemo->message = 'A new schedule request has been generated by '.Auth::user()->f_name.' for '.$booking->start_date.' at '.Carbon::parse($booking->start_time)->format('g:i A').'. Schedule Id is NUR'.$booking->id;
        $objDemo->userName = $user->f_name.' '.$user->m_name.' '.$user->l_name;
        $objDemo->userMobileNumber = $user->country_code.'-'.$user->mobile_number;
        $issend = Mail::to($emails)->send(new MailHelper($objDemo));
        return true;
    }
}
