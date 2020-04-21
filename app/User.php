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
        'role_id', 'f_name','m_name','l_name', 'email','height','weight','language', 'country_code','gender','dob', 'mobile_number','otp','is_blocked','is_notify', 'mobile_number_verified', 'email_verified', 'email_activation_token', 'city', 'state', 'street','zipcode','profile_image','password','carepack_mail_token','additional_info','document','alt_contact_name','alt_contact_no'    ];

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

}
