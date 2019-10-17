<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name','mobile_number','relation_id'
    ];
}
