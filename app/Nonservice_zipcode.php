<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nonservice_zipcode extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'nonservice_zipcode';
    
    protected $fillable = [
        'caregiver_id', 'zipcode'
    ];
}
