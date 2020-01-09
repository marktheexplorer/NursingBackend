<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Client;

class Helper extends Model
{
    /**
     * send notificaton
     *
     * @param  $latlng
     * @return \Illuminate\Http\Response
     */

    public static function sendNotifications($userId, $bookingId, $title, $message)
    { 
       $input['user_id'] = $userId;
       $input['booking_id'] = $bookingId;
       $input['title'] = $title;
       $input['message'] = $message;
       $input['is_read'] = 0;

       if(Notification::create($input)){
            return true;
       }else{
            return false;
       }
    }

    public static function sendTwilioMessage($mobileNumber ,$countryCode, $message)
    {   
        $client = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));

        try{
            $response = $client->messages->create(
                // the number you'd like to send the message to
                '+'.$countryCode.$mobileNumber ,
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+13343397984',
                    // the body of the text message you'd like to send
                    'body' => $message
                )
            )->toArray();

        }catch(\Exception $e){
            $response = false;
        }

        return $response;
    }

}
