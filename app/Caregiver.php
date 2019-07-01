<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'caregiver';
    
    protected $fillable = [
        'user_id', 'service', 'min_price','max_price','description','gender','dob'
    ];
}
