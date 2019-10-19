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
Route::get('/sysmg/accounts', 'PagesController@sysmg_accounts');
//Route::get('/sysmg/units', 'PagesController@sysmg_units');
Route::get('/sysmg/signatories', 'PagesController@sysmg_signatories');

Route::resource('/sysmg/units', 'FunctionalUnitsController');
Route::post('/sysmg/units/search', 'FunctionalUnitsController@search');

Auth::routes();


