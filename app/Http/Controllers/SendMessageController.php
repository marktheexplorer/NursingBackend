<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Validator;
use Twilio\Rest\Client;

class SendMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::orderBy('created_at', 'DESC')->get();
        return view('sendmsg.index' , compact('messages'));   
    }

    public function create()
    {
    	$users = User::select('id','f_name','m_name','l_name','country_code','mobile_number')->where('role_id' , '!=', 1)->get()->toArray();
    	return view('sendmsg.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $input = $request->input();
        $validator = validator::make($input,[
            'user_id' => 'required',
            'msg' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
                $user = User::where('id',$input['user_id'])->first();

                $client = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
		        try{
		            $response = $client->messages->create(
		                // the number you'd like to send the message to
		                '+'.$user->country_code.$user->mobile_number ,
		                array(
		                    // A Twilio phone number you purchased at twilio.com/console
		                    'from' => '+13343397984',
		                    // the body of the text message you'd like to send
		                    'body' => $input['msg']
		                )
		            )->toArray();
		            
               		$msg = Message::create($input);
                	flash()->success('Message Sent successfully.');

		        }catch(\Exception $e){
                	flash()->error('Message not Sent successfully.');
		        }

                return redirect()->route('sendmsg.create');
    }
}
