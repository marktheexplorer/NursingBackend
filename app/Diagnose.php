<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnose extends Model
{
    protected $table = "diagnosis";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'is_blocked',
    ];

    public function diagnose(){
        return $this->hasOne('App\PatientProfile', 'diagnose_id', 'id');
    }
}
