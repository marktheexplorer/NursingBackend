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
	Route::resource('services', 'ServiceController');
	Route::resource('qualification', 'QualificationController');
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
	
	//Route::get('admin/searchzip', 'CaregiverController@searchzip');
	/*Route::get('admin/caregiver/searchzip', function(){
		echo "there is something....";
	});*/
});