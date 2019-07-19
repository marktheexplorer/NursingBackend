<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'name', 'email', 'address', 'country_code', 'mobile_number', 'password', 'mobile_number_verified', 'email_verified', 'email_activation_token', 'type', 'is_social', 'profile_image', 'location', 'city', 'state', 'country', 'otp', 'is_notify','dob','gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_activation_token',
    ];

    public function OauthAcessToken() {
        return $this->hasMany('App\OauthAccessToken');
    }

    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('mobile_number', $identifier)->first();
    }

    public function patient(){
        return $this->hasOne('App\PatientProfile', 'user_id', 'id');
    }

    public function service(){
        return $this->hasOne('App\Service_requests', 'service', 'id');
    }

}
