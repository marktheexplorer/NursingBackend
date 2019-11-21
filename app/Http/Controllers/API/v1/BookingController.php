<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Diagnose;
use App\Countyareas;
use App\Booking;
use App\Qualification;
use App\Relation;
use Validator;
use DB;
use Carbon\Carbon;
use App\AssignedCaregiver;
use Log;

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
        $user = Auth::user();
        $validator =  Validator::make($input,
            [
                'relation_id' => 'required|string',
                'booking_type' => 'required',
                'height' =>'required',
                'weight' =>'required',
                'pets' => 'required',
                'diagnosis_id' => 'required',
                'service_location_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'weekdays' => 'array',
                'weekdays.0' => 'sometimes|required',
                '24_hours' => 'required',
                'start_time' => 'required',
                'end_time' =>'required',
                'no_of_weeks' =>'required',
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
            return response()->json(['status_code' => 201 , 'message' => 'You already have a booking at the specified time. Do you want to override it?', 'data' => ["override_id" => $result['id']]]);
        }

        if(($input['booking_type'] == 'Daily') || ($input['booking_type'] == 'Select date') || ($input['booking_type'] == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($input['booking_type'] == 'Select from week'){
            $startDate = Carbon::now()->format('m/d/Y');
            $endDate = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');

            $dates = Self::getDates($startDate , $endDate , $input['weekdays']);
            $input['start_date'] = Carbon::parse($dates[0])->format('m/d/Y');
            $input['end_date'] = Carbon::parse(end($dates))->format('m/d/Y');
        }

        foreach ($input['diagnosis_id'] as $key => $value) {
            $input['diagnosis'][] = Diagnose::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['zipcode'] = $input['zip_code'];
        $input['user_id'] = $user->id;
        $input['weekdays'] = serialize($input['weekdays']);
        $input['diagnosis_id'] = serialize($input['diagnosis']);
        $input['status'] = 'Pending';
        $booking = Booking::create($input);

        if($booking){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Booking created successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Booking not created successfully.', 'data' => null]);
        }
    }

    public function validateBooking($startDate , $endDate , $startTime , $endTime, $bookingType, $relationId, $id, $weekDays, $type, $bookingId)
    {
        $result['status']=null;
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        if($type == 'add')
            $bookings = Booking::where('relation_id' , $relationId)->where('user_id', $id)->get();
        else
            $bookings = Booking::where('relation_id' , $relationId)->where('user_id', $id)->where('id','!=',$bookingId)->get();

        if($bookingType == 'Today' || $bookingType == 'Select date'){
            
            foreach ($bookings as $key => $booking) {
            $booking_startDate = Carbon::parse($booking->start_date);
            $booking_endDate = Carbon::parse($booking->end_date);

                if(($startDate->gte($booking_startDate) && $startDate->lte($booking_endDate))||($endDate->gte($booking_startDate) && $endDate->lte($booking_endDate))){

                    if (($startTime >= $booking->start_time && $startTime <= $booking->end_time)||($endTime >= $booking->start_time && $endTime <= $booking->end_time)) 
                    {
                        $result['id'] = $booking->id;
                        $result['status'] = true;
                    }
                }
            }
        }else if($bookingType == 'Daily'){
            foreach ($bookings as $key => $booking) {
            $booking_startDate = Carbon::parse($booking->start_date);
            $booking_endDate = Carbon::parse($booking->end_date);

                if(($startDate->gte($booking_startDate) && $startDate->lte($booking_endDate))||($endDate->gte($booking_startDate) && $endDate->lte($booking_endDate))){

                    if (($startTime >= $booking->start_time && $startTime <= $booking->end_time)||($endTime >= $booking->start_time && $endTime <= $booking->end_time)) 
                    {
                        $result['id'] = $booking->id;
                        $result['status'] = true;
                    }
                }else if(($booking_startDate->gte($startDate) && $booking_startDate->lte($endDate))||($booking_endDate->gte($startDate) && $booking_endDate->lte($endDate))){

                    if (($startTime >= $booking->start_time && $startTime <= $booking->end_time)||($endTime >= $booking->start_time && $endTime <= $booking->end_time)) 
                    {
                        $result['id'] = $booking->id;
                        $result['status'] = true;
                    }                     
                }
            }
        }else if($bookingType == 'Select from week'){
            foreach ($bookings as $key => $booking) {
            $booking_startDate = Carbon::parse($booking->start_date);
            $booking_endDate = Carbon::parse($booking->end_date);

            $dates = Self::getDates($startDate , $endDate , $weekDays);

                while($endDate->gte($startDate))
                {  
                    if(in_array($booking_startDate->format('Y-m-d'), $dates) || in_array($booking_endDate->format('Y-m-d'), $dates)){
                        $result['id'] = $booking->id;
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
        $booking = Booking::where('id', $input['booking_id'])->first();
        $validator =  Validator::make($input,
            [
                'booking_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
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
            return response()->json(['status_code' => 201 , 'message' => 'You already have a booking at the specified time. Do you want to override it?', 'data' => ["override_id" => $result['id']]]);
        }

        if(($booking->booking_type == 'Daily') || ($booking->booking_type == 'Select date') || ($booking->booking_type == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($booking->booking_type == 'Select from week'){
            $startDate = Carbon::now()->format('m/d/Y');
            $endDate = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');

            $dates = Self::getDates($startDate , $endDate , $input['weekdays']);
            $input['start_date'] = Carbon::parse($dates[0])->format('m/d/Y');
            $input['end_date'] = Carbon::parse(end($dates))->format('m/d/Y');
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['zipcode'] = $input['zip_code'];
        $input['weekdays'] = serialize($input['weekdays']);
        $booking->fill($input);

        if($booking->save()){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Booking updated successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Booking not updated successfully.', 'data' => null]);
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
        $booking = Booking::where('id', $input['override_id'])->first();
        $validator =  Validator::make($input,
            [
                'relation_id' => 'required|string',
                'booking_type' => 'required',
                'height' =>'required',
                'weight' =>'required',
                'pets' => 'required',
                'diagnosis_id' => 'required',
                'service_location_id' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'weekdays' => 'array',
                'weekdays.0' => 'sometimes|required',
                '24_hours' => 'required',
                'start_time' => 'required',
                'end_time' =>'required',
                'no_of_weeks' =>'required',
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
            $startDate = Carbon::now()->format('m/d/Y');
            $endDate = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');

            $dates = Self::getDates($startDate , $endDate , $input['weekdays']);
            $input['start_date'] = Carbon::parse($dates[0])->format('m/d/Y');
            $input['end_date'] = Carbon::parse(end($dates))->format('m/d/Y');
        }

        foreach ($input['diagnosis_id'] as $key => $value) {
            $input['diagnosis'][] = Diagnose::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['zipcode'] = $input['zip_code'];
        $input['user_id'] = $user->id;
        $input['weekdays'] = serialize($input['weekdays']);
        $input['diagnosis_id'] = serialize($input['diagnosis']);
        $input['status'] = 'Pending';
        $booking->fill($input);

        if($input['booking_id'] != null)
             Booking::where('id', $input['booking_id'])->delete();

        if($booking->save()){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Booking updated successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Booking not updated successfully.', 'data' => null]);
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
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Booking deleted successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Booking not deleted successfully.', 'data' => null]);
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

            $diagnosis = unserialize($value['diagnosis_id']);
            foreach ($diagnosis as $a => $value) {
                $diagnose[$a]= Diagnose::select('title')->where('id', $value)->get()->toArray()[0]['title'];
            }
            $bookings[$key]['diagnosis_id'] = $diagnose;
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
        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays')->where('status', 'Caregiver Request')->where('user_id' , Auth::id())->get();

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

            foreach ($value->caregivers as $k => $care) {
                $bookings[$key]['caregivers'][$k]['name'] = $care->caregiver->user->name;
                if($care->caregiver->user->profile_image == null || empty($care->caregiver->user->profile_image))
                    $bookings[$key]['caregivers'][$k]['profile_image'] = 'default.png';
                else
                    $bookings[$key]['caregivers'][$k]['profile_image'] = $care->caregiver->user->profile_image;
            
                $bookings[$key]['caregivers'][$k]['language'] = $care->caregiver->language;
                $bookings[$key]['caregivers'][$k]['description'] = $care->caregiver->description;
                $bookings[$key]['caregivers'][$k]['discipline'] = Qualification::select('name')->join('caregiver_attributes' ,'caregiver_attributes.value' , 'qualifications.id')->where('type' , 'qualification')->where('caregiver_id', $care->caregiver->user->id)->get()->toArray();
            }
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => '', 'data' => null]);
        }
    }

    public function request_for_booking(Request $request){

        $input = $request->input();
        $assign = AssignedCaregiver::where('booking_id' , $input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->update(array('status' => 'Final'));
        //Status Update
        Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Upcoming', 'caregiver_id' => $input['caregiver_id']));

        if($assign){
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
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'No Bookings', 'data' => null]);
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

            $bookings[$key]['caregivers']['name'] = $care->userCaregiver->name;
            $bookings[$key]['caregivers']['profile_image'] = $care->userCaregiver->profile_image == null ? 'default.png' : $care->userCaregiver->profile_image ;
            $bookings[$key]['caregivers']['language'] = $care->userCaregiver->language;
            $bookings[$key]['caregivers']['description'] = $care->userCaregiver->description;
            $bookings[$key]['caregivers']['discipline'] = Qualification::select('name')->join('caregiver_attributes' ,'caregiver_attributes.value' , 'qualifications.id')->where('type' , 'qualification')->where('caregiver_id', $care->userCaregiver->id)->get()->toArray();
        }

        if(count($bookings) > 0){
            return response()->json(['status_code' => $this->successStatus , 'message' => '', 'data' => $bookings]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => '', 'data' => null]);
        }
    }
}
