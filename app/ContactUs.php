<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'message',
    ];

    public function user(){
        return $this->hasOne('App\User' , 'id' ,'user_id');
    }
}
