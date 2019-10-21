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

Route::get('/', 'HomeController@home');
Route::get('/csm', 'PagesController@csm');
Route::get('/sysmg/signatories', 'PagesController@sysmg_signatories');


Route::get('/profiles/{id}', 'ProfilesController@show');
Route::get('/profiles/{id}/edit', 'ProfilesController@edit');

Route::put('/profiles/{id}', 'ProfilesController@update');

Route::get('/profiles/{id}/change_pass', 'ProfilesController@change_password');
Route::put('/profiles/{id}/change_pass', 'ProfilesController@update_password');



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


Route::resource('/sysmg/units', 'FunctionalUnitsController');
Route::post('/sysmg/units/search', 'FunctionalUnitsController@search');

Route::resource('/sysmg/signatories', 'SignatoriesController');
Route::post('/sysmg/signatories/search', 'SignatoriesController@search');

Auth::routes();


