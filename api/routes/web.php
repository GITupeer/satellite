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

Route::GET('/satellite/{title}', 'APIController@satellite');
Route::GET('/userposition', 'APIController@userposition');



Route::get('/API/cron', 'APIController@cron');
Route::get('/API/cron_satellite_info', 'APIController@cron_satellite_info');
Route::GET('/cron_category_info', 'APIController@cron_category_info');
Route::GET('/API/offsetRate', 'APIController@offsetRate');







Route::GET('/cron/get_satellite_data', 'Cron@get_satellite_data');   // one per day 00:00
Route::GET('/satellite/api/getPosition', 'SatelliteController@getPosition');   // one per day 00:00
Route::GET('/satellite/api/getOrbit/{satellie_id}', 'SatelliteController@getOrbit');   // one per day 00:00
Route::get('/satellite/api/get_position_of_satellites_xml/{bounds}/{userLat}/{userLng}', 'SatelliteController@get_position_of_satellites_xml');
Route::get('/satellite/api/get_position_of_satellites_json/{bounds}/{userLat}/{userLng}', 'SatelliteController@get_position_of_satellites_json');



Route::get('/satellite/cron/updatePosition', 'SatelliteController@updatePosition');




// ############ Upeer Finanse ####################
Route::get('/upeerFinanse/getMail', 'UpeerFinanseController@getMail');
Route::get('/upeerFinanse/getSaldo', 'UpeerFinanseController@getSaldo');
Route::get('/upeerFinanse/generateCorrectDate', 'UpeerFinanseController@generateCorrectDate');
Route::get('/upeerFinanse/MCCStat', 'UpeerFinanseController@MCCStat');
Route::get('/upeerFinanse/registerDevice/{token}', 'UpeerFinanseController@registerDevice');



// ##### TEKKEN #####
Route::get('/tekken/registerAccount/{login}/{pass}', 'TekkenController@registerAccount');
Route::post('/tekken/stworzTurniej/', 'TekkenController@stworzTurniej');
Route::get('/tekken/turniejInfo/{UID}', 'TekkenController@turniejInfo');
Route::post('/tekken/logowanie', 'TekkenController@logowanie');
Route::post('/tekken/dolaczDoTurnieju', 'TekkenController@dolaczDoTurnieju');
Route::get('/tekken/getTurnieje', 'TekkenController@getTurnieje');
Route::post('/tekken/dolaczUser/', 'TekkenController@dolaczUser');
Route::get('/tekken/tworzenieRozgrywki/{UID}', 'TekkenController@tworzenieRozgrywki');
Route::get('/tekken/getInforRogrywka/{UID}', 'TekkenController@getInforRogrywka');
Route::get('/tekken/postacie', 'TekkenController@postacie');
Route::get('/tekken/stan_gry/{id}/{stan}', 'TekkenController@stan_gry');
Route::get('/tekken/getInfoRozgrywka/{id}', 'TekkenController@getInfoRozgrywka');
Route::get('/tekken/updateBanPostaci/{id}/{json}/{gracz}', 'TekkenController@updateBanPostaci');
Route::get('/tekken/updateWybranaPostac/{id}/{postacID}/{postacNazwa}/{gracz}', 'TekkenController@updateWybranaPostac');
Route::get('/tekken/updateWynikRozgrywki/{id}/{gracz1}/{gracz2}', 'TekkenController@updateWynikSrozgrywki');
Route::get('/tekken/zakonczRozgrywke/{id}', 'TekkenController@zakonczRozgrywke');
Route::get('/tekken/noweRozdanie/{UID}', 'TekkenController@noweRozdanie');
