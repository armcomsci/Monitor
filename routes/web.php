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
    Route::get('/GetNotify','dashboardController@getNotify');

    Route::get('/Monitor','scoreboardController@index');
    Route::get('/DtMonitor/{Container}','scoreboardController@dataDt');
    Route::get('/DtOrderItem/{Container}','scoreboardController@dataOrderItem');
    Route::get('/GetCommentJob/{Container}','scoreboardController@dataComment');
    Route::get('/GetJobEmptyPort','scoreboardController@dataJob');
    Route::post('/SaveJob','scoreboardController@saveJob');
    // Route::post('/SaveClearJob','scoreboardController@saveClearJob');
    Route::post('/SaveTransferJob','scoreboardController@saveTransJob');
    Route::post('/SaveReceiveJob','scoreboardController@saveReceive');
    Route::get('/GetUserLogin','scoreboardController@getUserLogin');
    Route::post('/SaveRemark','scoreboardController@saveRemark');
    Route::post('/ClearRemark','scoreboardController@clearRemark');
    Route::get('/GetHistory','scoreboardController@getHistory');
    Route::get('/GetJobStatus','scoreboardController@getJobTransStatus');
    Route::get('/JobReceive','scoreboardController@getReceive');
    Route::post('/DataCloseJob','scoreboardController@dataCloseJob');
    Route::post('ConfirmCloseJob','scoreboardController@CloseJob');
    Route::get('/GetDataJobClose','scoreboardController@JobCloseAgo');
    
    Route::get('/ChangeEmpDriv','empDrivController@index');
    Route::post('/ChangeSaveEmp','empDrivController@save');

    Route::get('/ChangeTimeDriv','changeTimeController@index');
    Route::post('/SaveTimeEmp','changeTimeController@save');

    Route::get('/MonitorAll','monitorAdminController@index');
    Route::post('/FindJobInPort','monitorAdminController@findjob');
    Route::get('/JobInPortDetail/{container}','monitorAdminController@detail');
    Route::post('/CustItem','monitorAdminController@dataItem');

    Route::get('/AddEmpDrive','addEmpController@index');
    Route::post('/FilterEmp','addEmpController@filter_emp');
    Route::post('/SaveWorkDate','addEmpController@save');
    Route::post('/ChangeStatusEmp','addEmpController@change_status');

    Route::get('/AddCar','addCarController@index');
    Route::post('/GetEventSet','addCarController@GetEvent');
    Route::get('/EventCarSet','addCarController@event');
    Route::post('/SaveTranspDate','addCarController@saveDate');

    Route::get('/ReportEmpDriver','ReportController@EmpDriver');
    Route::post('/FindEmpRun','ReportController@find');
    Route::get('/ReportJobClose','ReportController@reportJobClose');
    Route::post('/FindJobClose','ReportController@findCloseJob');
    Route::get('/JobCloseOrderItem/{Container}','ReportController@JobCloseOrderItem');

    Route::get('/CancelContain','cancelContainController@index');
    Route::post('/ReturnFlagContainer','cancelContainController@confirmReturn');
});

