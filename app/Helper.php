<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

}
