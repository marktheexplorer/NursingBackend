<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Teck extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'title', 'mark_trip', 'mark_trip_other', 'start_location', 'end_location', 'start_date', 'end_date','start_time', 'end_time', 'threshold_min_time', 'threshold_max_time', 'eta', 'start_lat_lng', 'end_lat_lng', 'current_lat_lng', 'start_location_city', 'start_location_state', 'start_location_country', 'repetitions', 'is_notify', 'status', 'is_active', 'count',
    ];

   

}
