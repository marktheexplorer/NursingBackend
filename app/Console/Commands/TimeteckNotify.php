<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Teck;
use DB;

class TimeteckNotify extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timeteck:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification according to time teck start time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $notify_user = Teck::where('type', '=', 'my')->where('is_active', '=', 1)->where('status', '=', 1)->where('is_notify', '=', 1)->update(['status' => 1]);

        $notify_user = DB::select( DB::raw("SELECT tecks.user_id, fcm_users.fcm_reg_id FROM tecks join fcm_users on tecks.user_id = fcm_users.user_id where tecks.is_active = 1 and tecks.status = 1 and tecks.is_notify = 1"));

        echo "<pre>";
        print_r($notify_user);
        die;

        $api_key = $_ENV['APP_FCM_KEY'];
        $device_id = 'fgmPiSrzEQM:APA91bH5cXJR05xz-tR06G8_ZrMghy56UcuA4DQotMSJsiajRqvMkAAMYZwVoevPZGFejbwan83mNJ2EoJgliXrPUPid4T0bigPN7G0KYUtd0b22c9MLvMLEVEXJecP4XZ3YlZYal7UN';

        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';

        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
        //$api_key = 'AAAAKZLje1I:APbGQDw8FD...TjmtuINVB-g';
                    
        $fields = array (
            'registration_ids' => array ($device_id),
            'data' => array ("message" => 'Everything is ok...'),
            'title' => 'Title', 
            'body' => 'body content', 
            'sound' => 'default'
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }else{
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
        curl_close($ch);
        die;

        $url = "https://fcm.googleapis.com/fcm/send";
        $token = $device_token;
        $serverKey = $_ENV['APP_FCM_KEY'];
        $title = "Notification title";
        $body = "Hello I am from Your php server";
        $notification = array('title' =>$title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        if ($response === FALSE)
            die('FCM Send Error: ' . curl_error($ch));
        else
            print_r($response);
        curl_close($ch);
        die('its over...');



        $registrationIds = array( 'dQSjkNJS3gQ:APA91bGFCadIpD5lG7vO_S6SzX_YyZIk8mZCu1zqNat6ZGQx871sjYlTMrMqRKCfB46um-Hxg4XC09S6pYCXPj0JfRIQFmIIbek2cdEbvEKHtxuiI5QWJLOYwtHTZOhKKVr64lu8qA0d');
        // prep the bundle
        $msg = array(
            'message'   => 'push notification message from Time Teck',
            'title'     => 'Time Teck',
            'subtitle'  => '',
            'tickerText'    => '',
            'vibrate'   => 1,
            'sound'     => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon'
        );
        
        
        $fields = array(
            'registration_ids'  => $registrationIds,    // user device token
            'data'          => $msg
        );
         
        $headers = array(
            'Authorization: key='.$_ENV['APP_FCM_KEY'],
            'Content-Type: application/json'
        );
         
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        print_r($ch);
        $result = curl_exec($ch );
        print_r($result);
        curl_close($ch);
    }
}
