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

// other pages
Route::get('/unauthorized', 'PagesController@unauthorized');
Route::get('/', 'HomeController@home');

// profile 
Route::get('/profiles/{id}', 'ProfilesController@show');
Route::get('/profiles/{id}/edit', 'ProfilesController@edit');
Route::put('/profiles/{id}', 'ProfilesController@update');
Route::get('/profiles/{id}/change_pass', 'ProfilesController@change_password');
Route::put('/profiles/{id}/change_pass', 'ProfilesController@update_password');

// user accounts
Route::resource('/sysmg/accounts', 'UsersController');
Route::get('/sysmg/accounts/create/employee', 'UsersController@create_employee');
Route::get('/sysmg/accounts/create/manager', 'UsersController@create_manager');
Route::get('/sysmg/accounts/create/admin', 'UsersController@create_admin');
Route::post('/sysmg/accounts/create/employee', 'UsersController@store_employee');
Route::post('/sysmg/accounts/create/manager', 'UsersController@store_manager');
Route::post('/sysmg/accounts/create/admin', 'UsersController@store_admin');
Route::post('/sysmg/accounts/search', 'UsersController@search');
Route::get('/sysmg/accounts/{id}/change_pass', 'UsersController@change_password');
Route::put('/sysmg/accounts/{id}/change_pass', 'UsersController@update_password');

// functional units
Route::resource('/sysmg/units', 'FunctionalUnitsController');
Route::post('/sysmg/units/search', 'FunctionalUnitsController@search');

// signatories
Route::resource('/sysmg/signatories', 'SignatoriesController');
Route::post('/sysmg/signatories/search', 'SignatoriesController@search');

// management review
Route::resource('/manrev', 'ManagementReviewsController');
Route::get('/manrev/action_plan/{id}', 'ManagementReviewsController@download_action_plan');
Route::get('/manrev/attendance/{id}', 'ManagementReviewsController@download_attendance');
Route::get('/manrev/minutes/{id}', 'ManagementReviewsController@download_minutes');
Route::get('/manrev/presentation_slide/{id}', 'ManagementReviewsController@download_presentation_slide');
Route::get('/manrev/agenda_memo/{id}', 'ManagementReviewsController@download_agenda_memo');
Route::get('/manrev/all_files/{id}', 'ManagementReviewsController@download_all_files');
Route::post('/manrev/search', 'ManagementReviewsController@search');

//quality manual documentation
Route::resource('/qmsd', 'QualityManualDocumentationsController');
Route::post('/qmsd/search', 'QualityManualDocumentationsController@search');
Route::get('/qmsd/manual_doc/{id}', 'QualityManualDocumentationsController@manual_doc');

Auth::routes();


