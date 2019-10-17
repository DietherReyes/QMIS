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

Route::get('/', function () {
    return view('CSM');
});

Route::get('/system_management/account_management', function () {
    return view('SYSMG_accounts');
});

Route::get('/system_management/functional_units', function () {
    return view('SYSMG_functional_units');
});

Route::get('/system_management/signatories', function () {
    return view('SYSMG_signatories');
});
