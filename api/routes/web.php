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
    return view('welcome');
});


Route::get('/API/cron', 'APIController@cron');
Route::get('/API/cron_satellite_info', 'APIController@cron_satellite_info');
Route::get('/API/test', 'APIController@test');
Route::get('/get_position', 'APIController@get_position');
Route::GET('/satellite/{title}', 'APIController@satellite');
