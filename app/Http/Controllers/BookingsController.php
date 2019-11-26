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
        $booking->start_time = $input['todaystarttime'];
        $booking->end_time = $input['todayendtime'];
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
        $booking['start_time'] = $input['todaystarttime'];
        $booking['end_time'] = $input['todayendtime'];
        $booking['address'] = $input['address'];
        $booking['city'] = $input['city'];
        $booking['state'] = $input['state'];
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
       
        return view('bookings.daily_form' , compact('booking','caregivers','diagnosis','assignedCaregivers','assignedCaregiversId'));
    }

    public function update_daily_form(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,
            [
                'city' => 'required',
                'start_date'=>'required',
                'end_date'=>'required',
                '24_hours' => 'required',
                'booking_id' => 'required|string|max:40',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'zipcode' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $booking = Booking::findOrFail($input['booking_id']);
        $booking->start_date = Carbon::parse($input['start_date'])->format('m/d/Y');
        $booking->end_date = Carbon::parse($input['end_date'])->format('m/d/Y');        
        $booking->start_time = $input['todaystarttime'];
        $booking->end_time = $input['todayendtime'];
        $booking->address = $input['address'];
        $booking->city = $input['city'];
        $booking->state = $input['state'];
        $booking->zipcode = $input['zipcode'];
        $booking->is_full_day = $input['is_full_day'];
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

        return view('bookings.select_from_week_form' , compact('booking','caregivers','diagnosis','assignedCaregivers','assignedCaregiversId')); 
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
                'city' => 'required',
                'zipcode' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $booking = Booking::findOrFail($input['booking_id']);
        $booking->weekdays = serialize($input['weekdays']);
        $booking->no_of_weeks = $input['no_of_weeks'];
        $booking->start_time = $input['todaystarttime'];
        $booking->end_time = $input['todayendtime'];
        $booking->address = $input['address'];
        $booking->city = $input['city'];
        $booking->state = $input['state'];
        $booking->zipcode = $input['zipcode'];
        $booking->is_full_day = $input['is_full_day'];
        if($input['is_full_day']){
            $booking->start_time = '00:00:00';
            $booking->end_time = '23:59:59';
        }
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
                'message' => 'Booking can not be deleted.Please try again',
            );
        }
        return json_encode($response);
    }
}