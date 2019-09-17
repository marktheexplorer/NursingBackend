<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'API\v1'], function(){
	Route::post('register', 'UserController@register');
	Route::post('login', 'UserController@login');
	Route::post('reset-password', 'UserController@resetPassword');
	Route::post('check-status', 'UserController@checkUserStatus');
	Route::post('verify-otp', 'UserController@verifyOtp');
	Route::post('send-otp', 'UserController@sendOtp');
	Route::post('getDisciplineList', 'UserController@getDisciplineList');
	Route::post('getServices', 'UserController@getServices');
	Route::post('getDiagnose', 'UserController@getDiagnose');
	Route::post('getCounty', 'UserController@getCounty');
	Route::post('getCountyArea', 'UserController@getCountyArea');
	Route::post('addServiceRequest', 'UserController@addServiceRequest');
	Route::post('updateServiceRequest', 'UserController@updateServiceRequest');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'API\v1'], function(){
	Route::post('set-notification', 'UserController@setNotification');
	Route::post('upload-image', 'UserController@uploadProfileImage');
	Route::post('edit-profile', 'UserController@editProfileDetails');
	Route::get('details', 'UserController@details');
	Route::post('change-password', 'UserController@changePassword');
	Route::post('current-location', 'UserController@getCurrentLocation');
	Route::post('logout', 'UserController@logout');
	Route::get('dashboard', 'HomeController@dashboard');
	Route::post('contact-us', 'HomeController@savecontactusData');
	Route::post('feedback', 'HomeController@addFeedback');
});
