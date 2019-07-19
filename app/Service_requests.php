<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service_requests extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'service_requests';

    protected $fillable = [
        'user_id', 'location', 'city','state','zip','country','service', 'min_expected_bill', 'max_expected_bill', 'start_time', 'end_time', 'start_date', 'end_date', 'schedule_request_id',  'status'
    ];
}
