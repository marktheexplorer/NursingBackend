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
	Route::post('forgot-password', 'UserController@forgotPassword');
	Route::post('verify-otp', 'UserController@verifyOtp');
	Route::post('reset-password', 'UserController@resetPassword');
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'API\v1'], function(){
	Route::post('change-password', 'UserController@changePassword');
	Route::post('logout', 'UserController@logout');
	Route::post('upload-image', 'UserController@uploadProfileImage');
	Route::post('set-notification', 'UserController@setNotification');
	Route::post('edit-profile', 'UserController@editProfileDetails');
	Route::get('details', 'UserController@details');
	Route::post('current-location', 'UserController@getCurrentLocation');
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
