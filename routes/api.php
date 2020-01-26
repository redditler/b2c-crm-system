<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'api'], function () {

    Route::group(['middleware' => 'ip.attempts'], function () {
        Route::post('/takedata', 'LeedController@addLeeds')->name('add-leeds');
        Route::post('/check-in', 'CheckInController@addWorker')->name('add-worker');
        Route::post('/takedata-promo', 'LeedController@addLeedsPromo')->name('add-leeds-promo');
    });

    Route::get('/getregions', 'LeedController@getRegions')->name('get-regions');
    Route::get('/getBranches', 'LeedController@getBranches')->name('get-branches');
    Route::get('/get-city', 'CheckInController@getCity')->name('get-city');
    Route::get('/getip', 'LeedController@getIp')->name('get-ip');
});
