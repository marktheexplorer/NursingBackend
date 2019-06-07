<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    /**
     * get address from latlng function
     *
     * @param  $latlng
     * @return \Illuminate\Http\Response
     */
    public static function geocode($latlng)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latlng."&key=".env('GOOGLE_API_KEY');
     
        $resp_json = file_get_contents($url);
         
        $resp = json_decode($resp_json, true);
     
        $data_arr = array();  

        if ($resp['status']=='OK')
        {  
            foreach($resp['results'][0]['address_components'] as $addr) {
                if ($addr['types'][0] == 'country')
                    $data_arr['country'] = $addr['long_name'];
                if ($addr['types'][0] == 'administrative_area_level_1')
                    $data_arr['state'] = $addr['long_name'];
                if ($addr['types'][0] == 'locality')
                    $data_arr['city'] = $addr['long_name'];
            }
            return $data_arr; 
        } else { 
            return false;
        }
    }
}
