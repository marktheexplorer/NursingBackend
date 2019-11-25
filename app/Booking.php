<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

	protected $table = "bookings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'relation_id','height','weight','pets','diagnosis_id','service_location_id','address','city','state','country','zipcode','booking_type','start_date','end_date','24_hours','no_of_weeks','timezone','weekdays','start_time','end_time','status','caregiver_id'
    ];

    public function user(){
        return $this->hasOne('App\User' , 'id' ,'user_id');
    }

    public function userCaregiver(){
        return $this->belongsTo('App\Caregiver' , 'caregiver_id' ,'id');
    }

    public function relation(){
        return $this->hasOne('App\UserRelation' , 'id' ,'relation_id');
    }

    public function service_location(){
        return $this->hasOne('App\Countyareas' , 'id' ,'service_location_id');
    }

    public function caregivers(){
        return $this->hasMany('App\AssignedCaregiver');
    }

}
