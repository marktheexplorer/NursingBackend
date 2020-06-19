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
use App\Helper;

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
            $emails = array("lmejer@24-7nursingcare.com", "fhernandez@24-7nursingcare.com", "mgomez@24-7nursingcare.com","vikrant.tyagi@saffrontech.net");

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
            $issend = Mail::to($emails)->send(new MailHelper($objDemo));
            
            $numbers = ['+13055251495','+17862478888','+17863995955','+919891550561'];
            Helper::sendContactUsMsg($numbers, $user->f_name.' '.$user->l_name.' Contacted you - '.$input['message']); 
            
            return response()->json(['status_code'=> 200, 'message'=> 'Thanks for contacting us.', 'data' => null]);
        }
        else
            return response()->json(['status_code'=> 400, 'message'=> "Can't contact. Please try again." ,'data' => null]);
    }

    public function faqListing()
    {
        $user = Auth::user();
        $faqs = Faq::select('question',htmlspecialchars_decode('answer'))->where('role_id',$user->role_id)->orderBy('faq_order', 'ASC')->get();
    
        foreach($faqs as $faq){
            $faq->answer = strip_tags(html_entity_decode($faq->answer));
        }
        
        if (count($faqs) > 0)
            return response()->json(['status_code'=> 200, 'message'=> '', 'data' => $faqs]);
        else
            return response()->json(['status_code'=> 400, 'message'=> "No FAQs" ,'data' => null]);
    }
   
}
