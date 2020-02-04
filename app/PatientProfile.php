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
        'user_id', 'diagnose_id', 'availability', 'disciplines', 'long_term', 'pets', 'pets_description', 'alt_contact_name', 'alt_contact_no'
    ];
}
