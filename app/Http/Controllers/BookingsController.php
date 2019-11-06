<?php

namespace App\Http\Controllers;
use App\Booking;

use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index(){
    	$bookings = Booking::get();
        return view('bookings.index' , compact('bookings')); 
    }
}
