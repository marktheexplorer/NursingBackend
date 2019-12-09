<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Charts;
use DB;
use App\Faq;
use App\Service;
use App\Diagnose;
use App\Booking;
use App\ContactUs;

class DashboardController extends Controller{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		$this->middleware('auth');
		$this->middleware('preventBackHistory');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index(){
		$users['patients'] = User::where('role_id', 3)->count(); 
		$users['caregivers'] = User::where('role_id', 2)->count(); 

		$services = Service::count();
		$diagnosis = Diagnose::count();
		$bookings = Booking::count();
		$pendingBookings = Booking::where('status', 'Pending')->count();

		$contactUs = ContactUs::count();
		$faqs = Faq::count();

		$today_users = User::whereDate('created_at', today())->count();
		$yesterday_users = User::whereDate('created_at', today()->subDays(1))->count();
		$users_2_days_ago = User::whereDate('created_at', today()->subDays(2))->count();

		$monthlyUsers = User::all();
		//dd($monthlyUsers)
		$chart = Charts::multiDatabase('line', 'highcharts')
					->title('User Details')
					->dataset('Total Users', $monthlyUsers)
					->dataset('Active Users', $monthlyUsers)
					->dimensions(1000, 500)
					->colors(['red', 'green', 'blue', 'yellow', 'orange', 'cyan', 'magenta'])
					->lastByDay();
					//->groupByMonth(date('Y'), true);

		$chart1= Charts::multiDatabase('line', 'highcharts')
					->title('User Details')
					->dataset('Total Users', $monthlyUsers)
					->dataset('Active Users', $monthlyUsers)
					->dimensions(1000, 500)
					->colors(['red', 'green', 'blue', 'yellow', 'orange', 'cyan', 'magenta'])
					->lastByDay();
					//->groupByMonth(date('Y'), true);
		
		/*$chart = new UserChart; 
		$chart->create('pie', 'highcharts');
		$chart->labels(['2 days ago', 'Yesterday', 'Today']);
		$chart->dataset('Dataset', 'line', [$users_2_days_ago, $yesterday_users, $today_users]);*/

		return view('dashboard', compact('users', 'contactUs', 'chart', 'faqs' ,'services','diagnosis','bookings','pendingBookings'));
	}
}
