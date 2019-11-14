<?php

namespace App\Http\Controllers;
use App\Booking;
use App\Caregiver;
use App\Diagnose;
use App\User;
use App\AssignedCaregiver;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index(){
    	$bookings = Booking::get();
        return view('bookings.index' , compact('bookings')); 
    }

    public function show($id){

    	$booking = Booking::findOrFail($id);
    	$caregivers = User::select('users.*','caregiver.id as caregiverId')->join('caregiver','caregiver.user_id','users.id')->orderBy('users.name','asc')->get();

        $assigned_caregivers = AssignedCaregiver::where('booking_id',$id)->get();
        $assignedCaregivers = array();
        foreach ($assigned_caregivers as $key => $value) {
            $assignedCaregivers[] = $value->caregiver_id;
            $assignedCaregiversName[] = $value->caregiver->user->name;
        }

    	foreach (unserialize($booking->diagnosis_id) as $key => $value) {
    		$diagnosis[] = Diagnose::select('title')->where('id', $value)->get()[0]->title;
    	}
    	$diagnosis = implode(',', $diagnosis);

    	return view('bookings.view' , compact('booking','caregivers','diagnosis','assignedCaregivers','assignedCaregiversName')); 

    }

    public function assign(Request $request){

        $input = $request->input();
        $exists = AssignedCaregiver::where('booking_id',$input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->get();
        if(count($exists) < 1){

            AssignedCaregiver::insert(['booking_id'=>$input['booking_id'],'caregiver_id'=> $input['caregiver_id']]);
            //Status Update
            Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Caregiver Assigned'));
            flash()->success("Caregiver assigned.");
        }else{
            
            AssignedCaregiver::where('booking_id',$input['booking_id'])->where('caregiver_id', $input['caregiver_id'])->delete();
            flash()->success("Caregiver removed.");

            $isexist = AssignedCaregiver::where('booking_id',$input['booking_id'])->get();
            if(count($isexist) < 1){
                //change status 
                Booking::where('id', '=', $input['booking_id'])->update(array('status' =>  'Booking Request.'));
            }

        }

        return redirect()->back();
    }
}
