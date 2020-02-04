<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\ContactUs;
use App\Faq;
use Validator;
use App\User;
use App\Mail\MailHelper;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * dashboard api
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
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

        $user = User::findOrFail(Auth::id());
        if ($contactus->save()){

            $objDemo = new \stdClass();
            $objDemo->sender = env('APP_NAME');
            $objDemo->receiver = ucfirst('Admin');
            $objDemo->type = 'contact_us_mail';
            $objDemo->subject = 'Contact Us Details';
            $objDemo->mail_from = env('MAIL_FROM_EMAIL');
            $objDemo->mail_from_name = env('MAIL_FROM_NAME');
            $objDemo->message = $input['message'];
            $objDemo->userName = $user->f_name.' '.$user->m_name.' '.$user->l_name;
            $objDemo->userMobileNumber = $user->country_code.'-'.$user->mobile_number;
            $issend = Mail::to('Lmejer@24-7nursingcare.com')->send(new MailHelper($objDemo));

            return response()->json(['status_code'=> 200, 'message'=> 'Thanks for contacting us.', 'data' => null]);
        }
        else
            return response()->json(['status_code'=> 400, 'message'=> "Can't contact. Please try again." ,'data' => null]);
    }

    public function faqListing()
    {
        $faqs = Faq::select('question',htmlspecialchars_decode('answer'))->orderBy('faq_order', 'ASC')->get();

        if (count($faqs) > 0)
            return response()->json(['status_code'=> 200, 'message'=> '', 'data' => $faqs]);
        else
            return response()->json(['status_code'=> 400, 'message'=> "No FAQs" ,'data' => null]);
    }
   
}
