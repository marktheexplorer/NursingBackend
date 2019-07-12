<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaregiverAttribute extends Model
{
     protected $table = "caregiver_attributes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'caregiver_id', 'value','type'
    ];

}
