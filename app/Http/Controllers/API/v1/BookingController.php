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
use Validator;
use DB;
use Carbon\Carbon;
use App\AssignedCaregiver;

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
            ]
        );

        if($input['booking_type'] == 'Select from week' )
            $validator = Validator::make($input, ['weekdays.0' => 'required'],['weekdays.0.required' => 'Weekdays is required.']);

        if ($validator->fails()) {
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
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
             $input['start_date'] = Carbon::now()->format('m/d/Y');
             $input['end_date'] = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');
        }

        foreach ($input['diagnosis_id'] as $key => $value) {
            $input['diagnosis'][] = Diagnose::select('id')->where('title', 'like', '%'.$value.'%')->first()->id;
        }

        $input['service_location_id'] = Countyareas::select('id')->where('area', 'like', '%'.$input['service_location_id'].'%')->first()->id;

        $input['user_id'] = $user->id;
        $input['weekdays'] = serialize($input['weekdays']);
        $input['diagnosis_id'] = serialize($input['diagnosis']);
        $input['status'] = 'Booking Request';
        $booking = Booking::create($input);

        if($booking){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Booking created successfully.', 'data' => null]);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Booking not created successfully.', 'data' => null]);
        }
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
                'start_time' => 'required',
                'end_time' =>'required',
            ]
        );

        if($booking->booking_type == 'Select from week' )
            $validator = Validator::make($input, ['weekdays.0' => 'required'],['weekdays.0.required' => 'Weekdays is required.']);

        if ($validator->fails()) {
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);
        }

        if(($booking->booking_type == 'Daily') || ($booking->booking_type == 'Select date') || ($booking->booking_type == 'Select from week')){
            if($input['24_hours'] == '1'){
                $input['start_time'] = '00:00:00';
                $input['end_time'] = '23:59:59';
            }
        }

        if($booking->booking_type == 'Select from week'){
             $input['start_date'] = Carbon::now()->format('m/d/Y');
             $input['end_date'] = Carbon::now()->addweek($input['no_of_weeks'])->format('m/d/Y');
        }

        $input['weekdays'] = serialize($input['weekdays']);
        $booking->fill($input);

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

        $bookings = Booking::where('user_id' , $user->id)->get()->toArray();
         
        foreach ($bookings as $key => $value) {
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
        $bookings = Booking::select('id','relation_id', 'start_date', 'end_date', '24_hours', 'start_time', 'end_time','weekdays')->where('status', 'Caregiver Assigned')->where('user_id' , Auth::id())->get();

        foreach ($bookings as $key => $value) {

            if($value['relation_id'] != null){
                $value['booking_for'] = $value->relation->name .' - '. $value->relation->user->name;
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
        $assign = AssignedCaregiver::where('booking_id' , $input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->update(array('status' => 'Caregiver Requested'));
        //Status Update
        Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Caregiver Requested'));

        if($assign){
            return response()->json(['status_code' => $this->successStatus , 'message' => 'Request sent successfully.', 'data' => '']);
        }else{
            return response()->json(['status_code' => $this->errorStatus , 'message' => 'Request not sent successfully.', 'data' => null]);
        }
    }
}
