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



Route::resource('/sysmg/accounts', 'UsersController');
Route::get('/sysmg/accounts/create/employee', 'UsersController@create_employee');
Route::get('/sysmg/accounts/create/manager', 'UsersController@create_manager');
Route::get('/sysmg/accounts/create/admin', 'UsersController@create_admin');
Route::post('/sysmg/accounts/create/employee', 'UsersController@store_employee');
Route::post('/sysmg/accounts/create/manager', 'UsersController@store_manager');
Route::post('/sysmg/accounts/create/admin', 'UsersController@store_admin');
Route::post('/sysmg/accounts/search', 'UsersController@search');


Route::resource('/sysmg/units', 'FunctionalUnitsController');
Route::post('/sysmg/units/search', 'FunctionalUnitsController@search');

Route::resource('/sysmg/signatories', 'SignatoriesController');
Route::post('/sysmg/signatories/search', 'SignatoriesController@search');

Auth::routes();


