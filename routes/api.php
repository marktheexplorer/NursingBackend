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
	Route::post('verify-otp', 'UserController@verifyOtp');
	Route::post('resend-otp', 'UserController@resendOtp');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'API\v1'], function(){
	Route::post('logout', 'UserController@logout');
	Route::post('upload-image', 'UserController@uploadProfileImage');
	Route::post('edit-profile', 'UserController@editProfileDetails');
	Route::get('details', 'UserController@details');
	Route::post('addUserRelation', 'UserController@addUserRelation');
	Route::post('destroyRelation', 'UserController@destroyUserRelation');
	Route::post('booking', 'BookingController@booking');
	Route::get('my_bookings', 'BookingController@my_bookings');
	Route::get('caregiverRequestsList', 'BookingController@caregiverRequestsList');
	Route::post('request-booking', 'BookingController@request_for_booking');
	Route::post('edit-booking', 'BookingController@edit_booking');
	Route::post('delete-booking', 'BookingController@delete_booking');
	Route::post('override-booking', 'BookingController@override_booking');
	Route::post('contact-us', 'HomeController@savecontactusData');
	Route::post('feedback', 'HomeController@addFeedback');
});
