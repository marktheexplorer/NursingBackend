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
	Route::get('signup/activate/{token}', 'UserController@signupActivate');
	Route::post('login', 'UserController@login');
	Route::post('social-login', 'UserController@socialLogin');
	Route::post('reset-password', 'UserController@resetPassword');
	Route::post('check-status', 'UserController@checkUserStatus');
	Route::post('verify-otp', 'UserController@verifyOtp');
	Route::post('send-otp', 'UserController@sendOtp');
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
	Route::post('teck-details', 'TeckController@teckDetails');
	Route::post('add-quick-teck', 'TeckController@addQuickTeck');
	Route::post('add-my-teck', 'TeckController@addMyTeck');
	Route::post('delete-teck', 'TeckController@deleteTeck');
	Route::post('inactivate-teck', 'TeckController@inactivateTeck');
	Route::post('notify-teck', 'TeckController@notifyTeck');
	
});
