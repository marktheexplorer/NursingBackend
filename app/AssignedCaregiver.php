<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedCaregiver extends Model
{
    protected $table = "assigned_caregivers";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'booking_id', 'caregiver_id', 'status', 'start_date', 'end_date', 'start_time', 'end_time','created_at', 'updated_at'
    ];

    public function caregiver(){
        return $this->hasOne('App\Caregiver' , 'id' ,'caregiver_id');
    }

    public function booking(){
        return $this->hasOne('App\Booking' , 'id' ,'booking_id');
    }

}
