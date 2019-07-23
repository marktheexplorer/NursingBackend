<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class RequestBooking extends Model{
    // table name
    protected $table = 'request_booking';

    public function setUpdatedAt($value){
	    // Do nothing.
	}
}