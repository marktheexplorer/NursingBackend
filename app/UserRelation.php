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

    public function relation(){
        return $this->hasOne('App\Relation' , 'id' ,'relation_id');
    }

    public function user(){
        return $this->hasOne('App\User' , 'id' ,'user_id');
    }
}
