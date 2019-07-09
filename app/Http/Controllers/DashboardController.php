<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Enquiry;
use Charts;
use DB;
use App\Faq;
use App\Service;
use App\Diagnose;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $users['total_users'] = User::count();
        $users['active_users'] = User::where('is_blocked', 0)->count(); 
        $users['blocked_users'] = User::where('is_blocked', 1)->count(); 
        $users['patients'] = User::where('role_id', 3)->count(); 

        $services = Service::count();
        $diagnosis = Diagnose::count();

        $enquiries = Enquiry::count();
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

        return view('dashboard', compact('users', 'enquiries', 'chart', 'faqs' ,'services','diagnosis'));
    }
}
