<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    protected $table = "patients_profiles";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'dob', 'diagnose_id','range','availability','gender','pin_code','additional_info','pets','pets_description','disciplines'
    ];
}
