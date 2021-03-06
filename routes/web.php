<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define('PROFILE_LANGUAGE', ["Creole", "English", "French", "Portuguese", "Spanish"]);
define('PROFILE_HEIGHT', ["Less then 5'", "5'","5' 1","5' 2","5' 3","5' 4","5' 5","5' 6","5' 7","5' 8","5' 9","5' 10","5' 11","6'","6' 1","6' 2","6' 3","6' 4","6' 5", "Greater then 6' 5"]);
define('PROFILE_WEIGHT', ["Less then 90 lbs", "90 lbs - 120 lbs", "121 lbs - 150 lbs", "151 lbs - 180 lbs", "181 lbs - 200 lbs", "201+ lbs"]);

Route::get('/', 'HomeController@home')->name('admin');
Auth::routes();
Route::get('signup/activate/{token}', 'API\v1\UserController@signupActivate');
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']],function(){
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	Route::get('/profile/signout', 'ProfileController@signout')->name('signout');
	Route::get('/profile', 'ProfileController@viewProfile')->name('profile');
	Route::get('/profile/edit', 'ProfileController@editProfile')->name('edit.profile');
	Route::post('/profile/update/{id}', 'ProfileController@updateProfile')->name('update.profile');
	Route::get('change-password', 'ProfileController@changePassword')->name('change.password');
	Route::post('change-password', 'ProfileController@updatePassword')->name('update.password');

	Route::get('patients/download_excel', 'PatientsController@download_excel')->name('patients.download_excel');
	Route::get('patients/active', 'PatientsController@activePatients')->name('patients.active');
	Route::get('patients/inactive', 'PatientsController@inactivePatients')->name('patients.inactive');
	Route::get('patients/blocked/{userId}', 'PatientsController@block');
	Route::resource('patients', 'PatientsController');

	Route::get('services/blocked/{userId}', 'ServiceController@block');
	Route::resource('services', 'ServiceController');

	Route::get('county/delete_area/{id}', 'CountyareaController@delete_area');
	Route::post('county/store_area', 'CountyareaController@store_area')->name('county.store_area');
	Route::get('county/blocked/{id}', 'CountyareaController@blocked');
	Route::resource('county', 'CountyareaController');

	Route::get('qualifications/blocked/{userId}', 'QualificationController@block');
	Route::resource('qualifications', 'QualificationController');
	Route::get('diagnosis/blocked/{userId}', 'DiagnoseController@block');
	Route::resource('diagnosis', 'DiagnoseController');
	Route::resource('faqs', 'FaqController');
	Route::get('reorder', 'FaqController@reorder')->name('faqs.reorder');
	Route::post('updateorder', 'FaqController@updateorder')->name('faqs.updateorder');
	Route::resource('cms', 'CmsPageController');
	Route::resource('relations', 'RelationController');
	Route::resource('contactUs', 'ContactUsController');
	Route::resource('sendmsg', 'SendMessageController');

	//caregiver controller it automatically route the default route	
	Route::get('caregiver/getzip', 'CaregiverController@getzip')->name('caregiver.getzip');
	Route::get('caregiver/searchcity', 'CaregiverController@searchcity')->name('caregiver.searchcity');
	Route::get('caregiver/statefromcity', 'CaregiverController@statefromcity')->name('caregiver.statefromcity');
	Route::get('caregiver/download_excel', 'CaregiverController@download_excel')->name('caregiver.download_excel');
	Route::get('caregiver/blocked/{userId}', 'CaregiverController@blocked');
	Route::resource('caregiver', 'CaregiverController');

	Route::get('bookings/index', 'BookingsController@index')->name('bookings.index');
	Route::get('bookings/{id}', 'BookingsController@show')->name('bookings.show');
	Route::delete('booking/destroy/{id}', 'BookingsController@destroy')->name('bookings.delete');
	Route::post('bookings/assign', 'BookingsController@assign')->name('bookings.assign');	
	Route::get('bookings/select_from_week_form/{id}', 'BookingsController@select_from_week_form')->name('bookings.select_from_week_form');
	Route::post('bookings/update_select_from_week_form', 'BookingsController@update_select_from_week_form')->name('bookings.update_select_from_week_form');
	Route::get('bookings/daily_form/{id}', 'BookingsController@daily_form')->name('bookings.daily_form');
	Route::post('bookings/update_daily_form', 'BookingsController@update_daily_form')->name('bookings.update_daily_form');	
	Route::get('bookings/select_date_form/{id}', 'BookingsController@select_date_form')->name('bookings.select_date_form');
	Route::post('bookings/update_select_date_form', 'BookingsController@update_select_date_form')->name('bookings.update_select_date_form');
	Route::get('bookings/complete_booking/{id}', 'BookingsController@complete_booking')->name('bookings.complete_booking');
	Route::get('bookings/manage/{id}', 'BookingsController@manageBooking')->name('bookings.manageBooking');
	Route::post('bookings/shifts/save', 'BookingsController@saveBookingDetails')->name('bookings.shiftSave');
});

Route::get('cms/{token}', 'CmsPageController@view_cms');
Route::get('confirm_careservice/{token}', 'BookingsController@confirm_careservice');
Route::post('upload_carepack_docs', 'BookingsController@upload_carepack_docs');
