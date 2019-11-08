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
        'id', 'booking_id', 'caregiver_id', 'status'
    ];

    public function caregiver(){
        return $this->hasOne('App\Caregiver' , 'id' ,'caregiver_id');
    }

}
