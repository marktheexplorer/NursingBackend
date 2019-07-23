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

Route::get('/', 'HomeController@home')->name('admin');
Auth::routes();
Route::get('signup/activate/{token}', 'API\v1\UserController@signupActivate');
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']],function(){
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	Route::get('/profile', 'ProfileController@viewProfile')->name('profile');
	Route::get('/profile/edit', 'ProfileController@editProfile')->name('edit.profile');
	Route::post('/profile/update/{id}', 'ProfileController@updateProfile')->name('update.profile');
	Route::get('change-password', 'ProfileController@changePassword')->name('change.password');
	Route::post('change-password', 'ProfileController@updatePassword')->name('update.password');
	Route::get('user/blocked/{userId}', 'UserController@block');
	Route::get('user/blocklist', 'UserController@blocklist')->name('users.blocklist');
	Route::resource('users', 'UserController');
	Route::get('patients/active', 'PatientsController@activePatients')->name('patients.active');
	Route::get('patients/inactive', 'PatientsController@inactivePatients')->name('patients.inactive');
	Route::get('patients/locationfromzip', 'PatientsController@locationfromzip');	
	Route::get('patients/blocked/{userId}', 'PatientsController@block');
	Route::resource('patients', 'PatientsController');
	Route::get('services/blocked/{userId}', 'ServiceController@block');
	Route::resource('services', 'ServiceController');
	Route::get('qualifications/blocked/{userId}', 'QualificationController@block');
	Route::resource('qualifications', 'QualificationController');
	Route::get('diagnosis/blocked/{userId}', 'DiagnoseController@block');
	Route::resource('diagnosis', 'DiagnoseController');
	Route::resource('faqs', 'FaqController');
	Route::get('reorder', 'FaqController@reorder')->name('faqs.reorder');
	Route::post('updateorder', 'FaqController@updateorder')->name('faqs.updateorder');
	Route::resource('cms', 'CmsPageController');
	Route::resource('enquiries', 'EnquiryController');

	//caregiver controller it automatically route the default route
	Route::get('caregiver/blocked/{userId}', 'CaregiverController@blocked');
	Route::get('caregiver/searchzip', 'CaregiverController@searchzip');
	Route::get('caregiver/locationfromzip', 'CaregiverController@locationfromzip');	
	Route::resource('caregiver', 'CaregiverController');
	
	
	Route::get('service_request/confirm_doc/{id}', 'ServiceRequestController@confirm_doc')->name('service_request.confirm_doc');
	Route::get('service_request/reschedule/{id}', 'ServiceRequestController@reschedule')->name('service_request.reschedule');
	Route::post('service_request/confirm_caregiver', 'ServiceRequestController@confirm_caregiver')->name('service_request.confirm_caregiver');
	Route::post('service_request/picked_caregiver', 'ServiceRequestController@picked_caregiver')->name('service_request.picked_caregiver');
	Route::post('service_request/save_request_caregivers', 'ServiceRequestController@save_request_caregivers')->name('service_request.save_request_caregivers');
	Route::post('service_request/assign', 'ServiceRequestController@assign')->name('service_request.assign');
	Route::get('service_request/caregiver_list/{userId}', 'ServiceRequestController@caregiver_list')->name('service_request.caregiver_list');
	Route::get('service_request/blocked/{userId}', 'ServiceRequestController@blocked');
	Route::resource('service_request', 'ServiceRequestController');
});

Route::get('cms/{token}', 'CmsPageController@view_cms');
Route::get('confirm_careservice/{token}', 'ServiceRequestController@confirm_careservice');
Route::post('upload_carepack_docs', 'ServiceRequestController@upload_carepack_docs');