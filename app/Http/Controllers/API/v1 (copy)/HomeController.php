<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\ContactUs;
use App\AppFeedback;
use App\Teck;
use Validator;

class HomeController extends Controller
{
    /**
     * dashboard api
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
       $tecks = Teck::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();

        if (count($tecks) > 0)
            return response()->json(['status_code'=> 200, 'message'=> null, 'data' => $tecks]);
        else
            return response()->json(['status_code'=> 400, 'message'=> 'No teck found.' ,'data' => null]);
    }

    /**
     * save contact us form data api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveContactusData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:300', 
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input  = $request->input();
        $input['user_id'] = Auth::id();
        $contactus = new ContactUs();
        $contactus->fill($input);

        if ($contactus->save())
            return response()->json(['status_code'=> 200, 'message'=> 'Thanks for contacting us.', 'data' => null]);
        else
            return response()->json(['status_code'=> 400, 'message'=> "Can't contact. Please try again." ,'data' => null]);
    }

    /**
     * App feedback api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric',
            'text' => 'nullable|string|max:300', 
        ]);

        if ($validator->fails()) 
            return response()->json(['status_code'=> 400, 'message'=> $validator->errors()->first(), 'data' => null]);

        $input  = $request->input();
        $input['user_id'] = Auth::id();
        $addfeedback = new AppFeedback();
        $addfeedback->fill($input);

        if ($addfeedback->save())
            return response()->json(['status_code'=> 200, 'message'=> 'Thanks for the feedback!', 'data' => null]);
        else
            return response()->json(['status_code'=> 400, 'message'=> 'Feedback cannot be added. Please try again!' ,'data' => null]);
    }
   
}