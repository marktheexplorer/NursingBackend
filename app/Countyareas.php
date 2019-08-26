<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class countyareas extends Model{
    protected $table = "county_areas";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'county','area', 'is_blocked', 'created_at', 'updated_at'
    ];
}
