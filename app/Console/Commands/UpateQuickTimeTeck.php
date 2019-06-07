<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use App\Teck;

class UpateQuickTimeTeck extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Quicktimeteck:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron work for de-activate all quick time teck on mid-night of day';

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
        $yesterday_date = date('d-m-Y', strtotime("-1 days"));

        //get all active quick teck of yesterday date
        $notify_user = DB::select("SELECT fcm_users.fcm_reg_id FROM tecks join fcm_users on fcm_users.user_id = tecks.user_id WHERE tecks.type = 'quick' and tecks.start_date = '$yesterday_date'");

        if(!empty($notify_user)){
            foreach($notify_user as $row){
                //send notification to each user
                $this->sendnotification($row->fcm_reg_id);
            }
            //update teck status to expired
            $notify_user = DB::update("update tecks set status = 2 WHERE type = 'quick' and start_date = '$yesterday_date'");
        }
    }

    public function sendnotification($device_id){
        $api_key = $_ENV['APP_FCM_KEY'];

        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array (
            'registration_ids' => array ($device_id),
            'data' => array ("message" => 'Your Quick Teck has been expired.'),
            'title' => 'Time Teck',
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
            die('    FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }
}