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



Route::get('/API/test', 'APIController@test');
Route::get('/get_position/{bounds}', 'APIController@get_position');
Route::get('/API/get_position_of_satellites_xml/{bounds}/{userLat}/{userLng}', 'APIController@get_position_of_satellites_xml');
Route::GET('/satellite/{title}', 'APIController@satellite');
Route::GET('/userposition', 'APIController@userposition');



Route::get('/API/cron', 'APIController@cron');
Route::get('/API/cron_satellite_info', 'APIController@cron_satellite_info');
Route::GET('/cron_category_info', 'APIController@cron_category_info');
Route::GET('/API/offsetRate', 'APIController@offsetRate');




Route::GET('/cron/get_satellite_in_orbit', 'Cron@get_satellite_in_orbit');