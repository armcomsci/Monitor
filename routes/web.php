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
Route::get('/updateData','LoginController@updateDT');

Route::group(['middleware' => ['auth','check.remember.token'] ], function () {
    Route::get('/Logout','LoginController@logout');

    Route::get('/','dashboardController@index');
    Route::get('/createView','dashboardController@createView');
    Route::get('/GpsCarAll','dashboardController@gpsCarAll');
    Route::get('/GetNotify','dashboardController@getNotify');
    Route::get('/GetRemarkEmpDriver','dashboardController@getRemarkDriver');

    Route::get('/ProfileEmp','generalController@profileEmp');
    Route::post('/ProfileEmpSave','generalController@profileEmpSave');
    Route::post('/ProfileEmpGet','generalController@profileEmpGet');
    Route::post('/ProfileEmpDelete','generalController@profileEmpDel');

    Route::get('/ProfileCar','generalController@profileCar');

    Route::get('/ProfileCarType','generalController@ProfileCarType');
    Route::post('/ProfileCarTypeSave','generalController@ProfileCarTypeSave');
    Route::post('/ProfileCarTypeGet','generalController@profileCarTypeGet');
    Route::post('/ProfileCarTypeDelete','generalController@profileCarDel');

    Route::get('/ProfileCarGroup','generalController@ProfileCarGroup');
    Route::post('/ProfileCarGroupSave','generalController@profileCarGroupSave');
    Route::post('/ProfileCarGroupGet','generalController@profileCarGroupGet');
    Route::post('/ProfileCarGroupDelete','generalController@profileCarGroupDel');

    Route::get('/ProfileRateCar','generalController@ProfileRateCar');

    Route::get('/ProfileFinance','generalController@ProfileFinance');
    Route::post('/ProfileFinanceSave','generalController@profileFinanceSave');
    Route::post('/ProfileFinanceGet','generalController@profileFinanceGet');
    Route::post('/ProfileFinanceDelete','generalController@profileFinanceDel');

    Route::get('/ProfileInsurance','generalController@ProfileInsurance');
    Route::post('/ProfileInsurerGet','generalController@profileInsuranceGet');
    Route::post('/ProfileInsuranceSave','generalController@profileInsuranceSave');
    Route::post('/ProfileInsuranceDelete','generalController@profileInsuranceDel');

    Route::get('/ProfileInsuranceType','generalController@ProfileInsuranceType');
    Route::post('/ProfileInsuranceTypeGet','generalController@profileInsuranceTypeGet');
    Route::post('/ProfileInsuranceTypeSave','generalController@profileInsuranceTypeSave');
    Route::post('/ProfileInsuranceTypeDelete','generalController@profileInsuranceTypeDel');

    Route::get('/ProfileRouteProductCode','generalController@ProfileRouteProductCode');
    Route::get('/ProfileRouteProduct','generalController@ProfileRouteProduct');
    Route::post('/ProfileRouteProductData','generalController@ProfileRouteProductData');
    Route::get('/GetlmCenTran','generalController@GetlmCenTran');
    Route::post('/ProfileRouteProductSave','generalController@ProfileRouteProductSave');
    Route::post('/ProfileRouteTransSave','generalController@ProfileRouteTransSave');

    Route::get('/ProfileSetCalTrans','generalController@ProfileSetCalTrans');
    Route::post('/profileSetCalTransGet','generalController@ProfileSetCalTransGet');
    Route::post('/ProfileSetCalTransSave','generalController@ProfileSetCalTransSave');

    Route::get('/ProfileCalTrans','generalController@ProfileCalTrans');
    Route::post('/profileCalTransGet','generalController@ProfileCalTransGet');
    Route::post('/ProfileCalTransSave','generalController@ProfileCalTransSave');

    Route::get('/ProfileTransport','generalController@ProfileTransport');

    Route::get('/ProfilePay','generalController@ProfilePay');
    Route::post('/ProfilePayGet','generalController@profilePayGet');
    Route::post('/ProfilePaySave','generalController@profilePaySave');
    Route::post('/ProfilePayDel','generalController@profilePayDel');
    Route::post('/ProfilePayChangeVat','generalController@profilePayChangeVat');
    Route::post('/ProfilePayChangeSt','generalController@profilePayChangeST');

    Route::get('/ProfileFund','generalController@ProfileFund');
    Route::post('/ProfileFundGet','generalController@profileFundGet');
    Route::post('/ProfileFundSave','generalController@profileFundSave');
    Route::post('/ProfileFundDel','generalController@profileFundDel');
    Route::post('/ProfileFundChangeVat','generalController@profileFundChangeVat');
    Route::post('/ProfileFundChangeSt','generalController@profileFundChangeST');

    Route::get('/ProfileTypeWithDraw','generalController@ProfileTypeWithDraw');

    Route::get('/ProfileTypeOil','generalController@ProfileTypeOil');
    Route::post('/ProfileOilTypeGet','generalController@profileOilGet');
    Route::post('/profileOilSave','generalController@profileOilSave');
    Route::post('/ProfileOilTypeDelete','generalController@profileOilDel');

    Route::get('/LeaveWork','leaveWorkController@leaveWork');
    Route::post('/LeaveWorkSave','leaveWorkController@save');
    Route::post('/LeaveWorkGet','leaveWorkController@getData');
    Route::post('/LeaveWorkDelete','leaveWorkController@del');

    Route::get('/settingUser','UserSettingController@settingUser');
    Route::post('/settingUserGet','UserSettingController@settingGet');
    Route::post('/settingUserSave','UserSettingController@settingSave');
    Route::post('/settingUserFlag','UserSettingController@settingChange');

    Route::get('/DataEmpDriv','CarSettingController@dataEmpDriv');
    Route::get('/EmpDrivGetData/{VehicleCode}','CarSettingController@GetData');
    Route::post('/GetSerieFromBrand','CarSettingController@GetSerie');
    Route::post('/DataEmpDrivSave','CarSettingController@save');

    Route::get('/RouteProvince','settingRouteController@RouteProvince');
    Route::get('/GetProvince','settingRouteController@GetProvince');
    Route::get('/GetAmpthaiTm','settingRouteController@GetAmpthaiTm');

    Route::get('/RouteBangkok','settingRouteController@RouteBangkok');
    Route::get('/GetSubZone','settingRouteController@SubZone');
    Route::get('/GetMarZone','settingRouteController@MarZone');
    Route::get('/GetMarToSubZone','settingRouteController@MarToSubZone');
    // Route::get('/GetSubZoneMart','settingRouteController@SubZoneToMart');

    Route::get('/RouteTranspot','settingRouteController@RouteTranspot');
    Route::get('/GetDataGrpTran','settingRouteController@GetDataGrpTran');
    Route::get('/GetDataCenTran','settingRouteController@GetDataCenTran');

    Route::get('/configOil','OilConfigController@configOil');
    Route::post('/configOilGetComp','OilConfigController@configOilGetComp');
    Route::post('/configOilSave','OilConfigController@configOilSave');

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
    Route::post('/ImgCust','scoreboardController@GetImgConfirm');
    Route::post('/ConfirmImgCust','scoreboardController@ConfirmImgCust');

    Route::any('/RateEmpDriv','RateEmpDrivController@setFrom');
    Route::post('/saveRateCarDriv','RateEmpDrivController@saveTitle');
    Route::post('/RateEmpDrivGetTitle','RateEmpDrivController@getTitle');
    Route::post('/RateEmpDrivGetSubTitle','RateEmpDrivController@getSubTitle');
    Route::post('/RateEmpDrivDeleteTitle','RateEmpDrivController@deleteTitle');
    Route::post('/saveRateSubTitleCarDriv','RateEmpDrivController@saveSubTitle');
    Route::any('/RateEmpDrivScore','RateEmpDrivController@rateEmp');
    Route::post('/RateProfileEmpDriv','RateEmpDrivController@proFileEmpDriv');
    Route::post('/RateGetSubTitle','RateEmpDrivController@proFileGetSubTitleRate');
    Route::post('/SaveRateEmpDriv','RateEmpDrivController@saveRateEmp');
    
    
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
    Route::post('/DetailStampDate','ReportController@detailRun');
    Route::get('/ReportJobClose','ReportController@reportJobClose');
    Route::post('/FindJobClose','ReportController@findCloseJob');
    Route::get('/JobCloseOrderItem/{Container}','ReportController@JobCloseOrderItem');
    Route::get('/ReportScore','ReportController@reportScore');
    Route::post('/FindScore','ReportController@findScore');
    Route::get('/ReportRemark','ReportController@reportRemark');
    Route::post('/FindRemark','ReportController@dataRemark');
    Route::get('/ReportCustConfirm','ReportController@CustConfirm');
    Route::get('/ReportEditCarDriv','ReportController@reportEditCarDriv');
    Route::post('/FindLogEditCarDriv','ReportController@findLogEditCar');
    Route::get('/ReportRateEmpDriv','ReportController@reportRate');
    Route::post('/FindRateEmpDriv','ReportController@dataRateEmpDriv');
    Route::post('/DetailRateEmpDriv','ReportController@detailRateEmp');
    Route::post('/ClearRateEmp','ReportController@ClearRateEmp');
    Route::get('/ExportRateEmpDriv','ReportController@exportExcelRate');
    Route::get('/ExportRateEmpYear','ReportController@exportExcelRateYear');
    Route::get('/ReportWorkDriv','ReportController@workDriv');
    Route::post('/FindLeaveWork','ReportController@dataWorkDriv');
    Route::post('/DetailEmpDrivWork','ReportController@detailEmpWork');
    Route::post('/ClearWorkEmp','ReportController@clearWorkEmp');
    Route::get('/ExportEmpDrivWork','ReportController@exportExcelWork');
    Route::get('/ExportEmpDrivWorkAll','ReportController@exportExcelWorkAll');
    Route::get('/ReportEmpDriveDt','ReportController@RateEmpDriveDt');
    Route::post('/GetTitleRate','ReportController@GetTitleRate');
    Route::post('/FindRateEmpDrivTitle','ReportController@detailRateEmpTitle');
    Route::get('/ExportRateEmpTitle','ReportController@exportExcelEmpRateTitle');

    Route::get('/CancelContain','cancelContainController@index');
    Route::post('/ReturnFlagContainer','cancelContainController@confirmReturn');

    Route::any('/AdminConfirmImg','confirmImgCustController@index');
    Route::post('/AdminConfirmImgCust','confirmImgCustController@confirm');

    Route::get('/PermissionUser','userPermissionController@index');
    Route::get('/GetPerMission/{menu_id}','userPermissionController@getPerMission');
    Route::post('/PermissionUserSave','userPermissionController@save');
    Route::get('/CreateUser','userPermissionController@createUser');
    Route::post('/userSave','userPermissionController@saveUser');
    Route::post('/userGet','userPermissionController@userGet');
    Route::post('/userChangeStatus','userPermissionController@changeStatusUser');

    Route::get('ApproveCarDriv','ApproveController@AppCarDriv');
    Route::post('AdminApporveEditCar','ApproveController@ConfirmEditCar');
});

