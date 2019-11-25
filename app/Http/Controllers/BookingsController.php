<?php

namespace App\Http\Controllers;
use App\Booking;
use App\Caregiver;
use App\Diagnose;
use App\User;
use App\AssignedCaregiver;
use Illuminate\Http\Request;
use Validator;

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

    public function edit($id){
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
        return view('bookings.edit' , compact('booking','caregivers','diagnosis','assignedCaregivers','assignedCaregiversId'));
    }

    public function today_update(Request $request){
        $input = $request->input();
        $validator =  Validator::make($input,[
            'todaystarttime' => 'required|string|max:40',
            'todayendtime' => 'required|string|max:40',
            'booking_id' => 'required|string|max:40',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        $booking = Booking::findOrFail($input['booking_id']);
        $booking->start_time = $input['todaystarttime'];
        $booking->end_time = $input['todayendtime'];
        $booking->save();

        flash()->success('Booking Update Successfully');
        return redirect()->route('bookings.edit', ['id' => $input['booking_id']]);
    }
}