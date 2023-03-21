<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/Login','LoginController@index')->name('login');
Route::post('/Checklogin','LoginController@checkLogin');

Route::group(['middleware' => ['auth','check.remember.token'] ], function () {
    Route::get('/Logout','LoginController@logout');

    Route::get('/','dashboardController@index');
    Route::get('/createView','dashboardController@createView');
    Route::get('/GpsCarAll','dashboardController@gpsCarAll');

    Route::get('/Monitor','scoreboardController@index');
    Route::get('/DtMonitor/{Container}','scoreboardController@dataDt');
    Route::get('/DtOrderItem/{Container}','scoreboardController@dataOrderItem');
    Route::get('/GetJobEmptyPort','scoreboardController@dataJob');
    Route::post('/SaveJob','scoreboardController@saveJob');
    // Route::post('/SaveClearJob','scoreboardController@saveClearJob');
    Route::post('/SaveTransferJob','scoreboardController@saveTransJob');
    Route::get('/GetUserLogin','scoreboardController@getUserLogin');
    Route::post('/SaveRemark','scoreboardController@saveRemark');
    Route::post('/ClearRemark','scoreboardController@clearRemark');
    Route::get('/GetHistory','scoreboardController@getHistory');
    Route::get('/GetJobStatus','scoreboardController@getJobTransStatus');
    Route::get('/JobReceive','scoreboardController@getReceive');
});

