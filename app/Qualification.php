<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{	
	protected $table = "qualifications";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
