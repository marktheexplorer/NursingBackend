<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'user_id', 'booking_id', 'title', 'message','is_read'
    ];

    public function user(){
        return $this->hasOne('App\User' , 'id' ,'user_id');
    }

    public function booking(){
        return $this->hasOne('App\Booking' , 'id' ,'booking_id');
    }
}
