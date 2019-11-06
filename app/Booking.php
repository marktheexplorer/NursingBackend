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
        'id', 'user_id', 'relation_id','height','weight','pets','diagnosis_id','service_location_id','address','county','state','country','zipcode','booking_type', 'caregiver_assigned','start_date','end_date','24_hours','no_of_weeks','timezone','weekdays','start_time','end_time'
    ];
}
