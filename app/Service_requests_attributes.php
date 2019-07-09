<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service_requests_attributes extends Model{
    public $table = 'service_requests_attributes';

    protected $fillable = [
        'service_request_id', 'value', 'type'
    ];
}