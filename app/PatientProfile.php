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
        'user_id', 'diagnose_id', 'f_name','m_name','l_name', 'height', 'weight', 'language', 'availability', 'pin_code', 'disciplines', 'long_term', 'pets', 'pets_description', 'additional_info', 'alt_contact_name', 'alt_contact_no'
    ];
}
