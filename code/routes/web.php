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
Route::get('/csm/statistics/insufficient_records', 'PagesController@insufficient_records');
Route::get('/unauthorized', 'PagesController@unauthorized');
Route::get('/deactivated', 'PagesController@deactivated');
Route::get('/', 'PagesController@home');

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

// logs
Route::resource('/sysmg/logs', 'LogsController');
Route::post('/sysmg/logs/search', 'LogsController@search');


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
Route::get('/qmsd/sections/idx', 'QualityManualDocumentationsController@get_sections');
Route::get('qmsd/sections/add', 'QualityManualDocumentationsController@add_section');
Route::post('/qmsd/sections/idx', 'QualityManualDocumentationsController@store_section');
Route::post('/qmsd/sections/idx/search', 'QualityManualDocumentationsController@search_section');
Route::get('/qmsd/sections/{id}/edit', 'QualityManualDocumentationsController@edit_section');
Route::put('/qmsd/sections/{id}/update', 'QualityManualDocumentationsController@update_section');

//customer satisfaction measurements
Route::resource('/csm', 'CustomerSatisfactionMeasurementsController');
Route::get('/csm/supporting_documents/{id}', 'CustomerSatisfactionMeasurementsController@download_supporting_documents');
Route::post('/csm/filter', 'CustomerSatisfactionMeasurementsController@filter');

Route::get('/csm/statistics/idx', 'CustomerSatisfactionMeasurementsController@graphs');
Route::post('/csm/statistics/idx', 'CustomerSatisfactionMeasurementsController@search_graphs');

Route::get('/csm/services/idx', 'CustomerSatisfactionMeasurementsController@get_services');
Route::get('/csm/services/add', 'CustomerSatisfactionMeasurementsController@add_service');
Route::post('/csm/services/idx', 'CustomerSatisfactionMeasurementsController@store_service');
Route::post('/csm/services/idx/search', 'CustomerSatisfactionMeasurementsController@search_service');
Route::get('/csm/services/{id}/edit', 'CustomerSatisfactionMeasurementsController@edit_service');
Route::put('/csm/services/{id}/update', 'CustomerSatisfactionMeasurementsController@update_service');

Route::get('/csm/addresses/idx', 'CustomerSatisfactionMeasurementsController@get_addresses');
Route::get('/csm/addresses/add', 'CustomerSatisfactionMeasurementsController@add_address');
Route::post('/csm/addresses/idx', 'CustomerSatisfactionMeasurementsController@store_address');
Route::post('/csm/addresses/idx/search', 'CustomerSatisfactionMeasurementsController@search_address');
Route::get('/csm/addresses/{id}/edit', 'CustomerSatisfactionMeasurementsController@edit_address');
Route::put('/csm/addresses/{id}/update', 'CustomerSatisfactionMeasurementsController@update_address');

Route::post('/csm/generate', 'SpreadsheetsController@generate');


Auth::routes();


