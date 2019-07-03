<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'qualifications';
	//protected $primaryKey = 'qualifications_id';

    protected $fillable = [
        'title', 'description'
    ];
}