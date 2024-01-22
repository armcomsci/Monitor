<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CarSettingController extends Controller
{
    //
    public function dataEmpDriv(){
        $CarDetail = DB::table('LMDBM.dbo.lmCarDriv as lmCarDriv')
                    ->join('LMDBM.dbo.lmEmpDriv as lmEmpDriv','lmCarDriv.EmpDriverCode','lmEmpDriv.EmpDriverCode')
                    ->join('LMDBM.dbo.lmCarType as lmCarType','lmCarDriv.CarTypeCode','lmCarType.CarTypeCode')
                    // ->leftjoin('LMSTemp_EditlmCarDriv as LMSTemp_EditlmCarDriv','lmCarDriv.VehicleCode','LMSTemp_EditlmCarDriv.vehicleCode')
                    ->select('lmCarDriv.*','lmEmpDriv.*','lmCarType.*')
                    ->selectRaw("(SELECT TOP(1) status_confirm FROM LMSTemp_EditlmCarDriv WHERE LMSTemp_EditlmCarDriv.vehicleCode = lmCarDriv.VehicleCode ORDER BY id DESC ) as status_confirm, (SELECT COUNT(VehicleCode) FROM LMSTemp_EditlmCarDriv WHERE LMSTemp_EditlmCarDriv.vehicleCode = lmCarDriv.VehicleCode AND status_confirm = 'Y' ) as confirmCount , (SELECT COUNT(VehicleCode) FROM LMSTemp_EditlmCarDriv WHERE LMSTemp_EditlmCarDriv.vehicleCode = lmCarDriv.VehicleCode AND status_confirm = 'R' ) as rejectCount , (SELECT TOP(1) created_time FROM LMSTemp_EditlmCarDriv WHERE LMSTemp_EditlmCarDriv.vehicleCode = lmCarDriv.VehicleCode ORDER BY id DESC ) as createdEdit  ")
                    
                    ->where('lmEmpDriv.Active','Y')
                    ->orderByRaw("status_confirm DESC")
                    ->get();
        // dd($CarDetail);
        return view('empDriver.dataEmpDriv',compact('CarDetail'));
    }

    public function GetSerie(Request $req){
        $BrandCode = $this->explodeVal($req->BrandCode);

        $Serie = DB::table('LMDBM.dbo.lmCarSerie')->where('CarBrandCode',$BrandCode)->get();
        
        return response()->json($Serie,200);
    }

    public function GetData($VehicleCode){

        $CarDetail = DB::table('LMDBM.dbo.lmCarDriv as lmCarDriv')
                    ->join('LMDBM.dbo.lmEmpDriv as lmEmpDriv','lmCarDriv.EmpDriverCode','lmEmpDriv.EmpDriverCode')
                    ->join('LMDBM.dbo.lmCarType as lmCarType','lmCarDriv.CarTypeCode','lmCarType.CarTypeCode')
                    ->join('LMDBM.dbo.lmCarDetail as lmCarDetail','lmCarDriv.VehicleCode','lmCarDetail.VehicleCode')
                    ->leftjoin('LMDBM.dbo.lmProThai_tm as lmProThai_tm','lmCarDetail.ProvinceID','lmProThai_tm.ProvinceID')
                    ->leftjoin('LMDBM.dbo.lmCarBand as lmCarBand','lmCarDetail.CarBrandCode','lmCarBand.CarBrandCode')
                    ->leftjoin('LMDBM.dbo.lmCarSerie as lmCarSerie','lmCarDetail.CarSerieCode','lmCarSerie.CarSerieCode')
                    ->leftjoin('LMDBM.dbo.lmOilType as lmOilType','lmCarDetail.OilTypeCode','lmOilType.OilTypeCode')
                    ->leftjoin('LMDBM.dbo.lmCoDriver as lmCoDriver','lmCarDriv.VehicleCode','lmCoDriver.VehicleCode')
                    ->leftjoin('LMDBM.dbo.lmSetFormula as lmSetFormula','lmCarDetail.FormulaCode','lmSetFormula.FormulaCode')
                    ->leftjoin('LMDBM.dbo.lmSetFormula_co as colmSetFormula','lmCoDriver.FormulaCode','colmSetFormula.FormulaCode')
                    ->leftjoin('LMDBM.dbo.lmCarInsure as lmCarInsure','lmCarDriv.VehicleCode','lmCarInsure.VehicleCode')
                    ->leftjoin('LMDBM.dbo.lmInsType as lmInsType','lmCarInsure.InsureTypeCode','lmInsType.InsureTypeCode')
                    ->leftjoin('LMDBM.dbo.lmInsComp as lmInsComp','lmCarInsure.InsureCompCode','lmInsComp.InsureCompCode')
                    ->leftjoin('LMDBM.dbo.lmCarForm as lmCarForm','lmCarDriv.VehicleCode','lmCarForm.VehicleCode')
                    ->leftjoin('LMDBM.dbo.lmCarFinan as lmCarFinan','lmCarDriv.VehicleCode','lmCarFinan.VehicleCode')
                    ->leftjoin('LMDBM.dbo.lmFinComp as lmFinComp','lmCarFinan.FinanceCode','lmFinComp.FinanceCode')
                    ->leftjoin('LMDBM.dbo.lmCarComp as lmCarComp','lmCarDriv.VehicleCode','lmCarComp.VehicleCode')
                    ->select('lmCarDetail.*'
                            ,'lmCarDetail.GPS as CarGPS'
                            ,'lmCoDriver.FormulaCode as coFormulaCode'
                            ,'lmCoDriver.CoEmpName'
                            ,'lmCoDriver.CoTel'
                            ,'lmCoDriver.CarComp'
                            ,'lmCarType.CarTypeName'
                            ,'lmProThai_tm.ProvinceName'
                            ,'lmCarBand.CarBrandName'
                            ,'lmCarSerie.CarSerieName'
                            ,'lmSetFormula.FormulaDetail'
                            ,'colmSetFormula.FormulaDetail as coFormulaDetail'
                            ,'lmOilType.OilTypeName'
                            ,'lmEmpDriv.EmpDriverCode'
                            ,'lmEmpDriv.EmpDriverName'
                            ,'lmEmpDriv.EmpDriverLastName'
                            ,'lmInsType.InsureTypeName'
                            ,'lmInsComp.InsureCompName'
                            ,'lmInsComp.InsureCompCode'
                            ,'lmCarInsure.PolicyInsureNo'
                            ,'lmCarInsure.InsureTypeCode'
                            ,'lmCarInsure.DateStart as CarInsureDateStart'
                            ,'lmCarInsure.DateEnd as CarInsureDateEnd'
                            ,'lmCarInsure.InsureCap'
                            ,'lmCarInsure.InsureValue'
                            ,'lmCarForm.FormulateNO'
                            ,'lmCarForm.InsureTypeCode as lmCarInsureTypeCode'
                            ,'lmCarForm.InsureCompCode as lmCarInsureCompCode'
                            ,'lmCarForm.DateStart as CarFormDateStart'
                            ,'lmCarForm.DateEnd as CarFormDateEnd'
                            ,'lmCarForm.FormulateValue'
                            ,'lmCarFinan.FinanceCode'
                            ,'lmFinComp.FinanceName'
                            ,'lmCarFinan.ContractNo'
                            ,'lmCarFinan.CarValue'
                            ,'lmCarFinan.CarTotalValue'
                            ,'lmCarFinan.CarDownValue'
                            ,'lmCarFinan.CarInterest'
                            ,'lmCarFinan.CarLoanValue'
                            ,'lmCarFinan.CarInstallmentValue'
                            ,'lmCarFinan.CarInstallment'
                            ,'lmCarFinan.CarInstallmentLeft'
                            ,'lmCarFinan.CarBalanceValue'
                            ,'lmCarFinan.DateStart as CarFinDateStart'
                            ,'lmCarFinan.DateEnd as CarFinDateEnd'
                            ,'lmCarFinan.DateRangeI as CarFinDateRangeI'
                            ,'lmCarFinan.DateRangeII as CarFinDateRangeII'
                            ,'lmCarFinan.Remark as CarFinRemark'
                            ,'lmCarFinan.Status as CarFinStatus'
                            ,'lmCarComp.CompCarValue'
                            ,'lmCarComp.CompCarTotal'
                            ,'lmCarComp.CompDownValue'
                            ,'lmCarComp.CompCarInterest'
                            ,'lmCarComp.CompInstallmentValue'
                            ,'lmCarComp.CompCarInstallment'
                            ,'lmCarComp.CompInstallmentLeft'
                            ,'lmCarComp.CompCarBalance'
                            ,'lmCarComp.Status as CarCompStatus'
                            ,'lmCarComp.DateStart as CarCompDateStart'
                            ,'lmCarComp.DateEnd as CarCompDateEnd'
                            ,'lmCarComp.DateRangeI as CarCompDateRangeI'
                            ,'lmCarComp.DateRangeII as CarCompDateRangeII'
                            ,'lmCarComp.Remark as CarCompRemark'
                            )
                    ->where('lmCarDriv.VehicleCode',$VehicleCode)
                    ->first();
        
        return response()->json($CarDetail);
    }

    public function save(Request $req){
        DB::beginTransaction();
        // dd($req);
        try { 
            $type_save      =   $req->type_save;
            if($type_save == 0){
                $VehicleCode    =   $req->VehicleCode;
                $CarTypeCode    =   $this->explodeVal($req->CarTypeCode);
                $ProvinceID     =   $this->explodeVal($req->ProvinceID);
                $CarBrandCode   =   $this->explodeVal($req->CarBrandCode);
                $CarSerieCode   =   $this->explodeVal($req->CarSerieCode);
                $RegistDate     =   Carbon::createFromFormat('d/m/Y',$req->RegistDate)->format('Y-m-d');
                $Year           =   $req->Year;
                $Color          =   $req->Color;
                $VehicleNo      =   $req->VehicleNo;
                $BodyNo         =   $req->BodyNo;
                $OilTypeCode    =   $this->explodeVal($req->OilTypeCode);
                $FormulaCode    =   $this->explodeVal($req->FormulaCode);
                $GPS            =   $req->GPS;
                $Remark         =   $req->Remark;
    
                $CarDetail['VehicleCode'] = $VehicleCode;
                $CarDetail['CarTypeCode'] = $CarTypeCode;
                $CarDetail['ProvinceID']  = $ProvinceID;
                $CarDetail['CarBrandCode'] = $CarBrandCode;
                $CarDetail['CarSerieCode'] = $CarSerieCode;
                $CarDetail['RegistDate']   = $RegistDate;
                $CarDetail['Year'] = $Year;
                $CarDetail['Color'] = $Color;
                $CarDetail['VehicleNo'] = $VehicleNo;
                $CarDetail['BodyNo'] = $BodyNo;
                $CarDetail['OilTypeCode'] = $OilTypeCode;
                $CarDetail['FormulaCode'] = $FormulaCode;
                $CarDetail['Status'] = 'Y';
                $CarDetail['GPS']    = $GPS;
                $CarDetail['Remark'] = $Remark;
    
                DB::table('LMDBM.dbo.lmCarDetail')->insert($CarDetail);
                
    
                $CoEmpName      =   $req->CoEmpName;
                $CarComp        =   $this->explodeVal($req->CarComp);
                $CoTel          =   $req->CoTel;
                $CoFormulaCode  =   $this->explodeVal($req->CoFormulaCode);
    
                $lmCoDriver['VehicleCode']  =   $VehicleCode;
                $lmCoDriver['FormulaCode']  =   $CoFormulaCode;
                $lmCoDriver['CoEmpName']    =   $CoEmpName;
                $lmCoDriver['CoTel']        =   $CoTel;
                $lmCoDriver['CarComp']      =   $CarComp;
    
                DB::table('LMDBM.dbo.lmCoDriver')->insert($lmCoDriver);
    
                $EmpDriv        =   $this->explodeVal($req->EmpDriv);
                $CarDriv =  DB::table('LMDBM.dbo.lmCarDriv')
                            ->where([
                                'VehicleCode' => $VehicleCode,
                                'EmpDriverCode' => $EmpDriv
                                ])
                            ->orderByDesc('ListNo')
                            ->first();
      
                if($CarDriv == ""){
                    $listNoCar = 1;
                }else{
                    $listNoCar =  $CarDriv->ListNo+1;
                }

                $lmCarDriv['EmpDriverCode'] = $EmpDriv;
                $lmCarDriv['ListNo']        = $listNoCar;
                $lmCarDriv['VehicleCode']   = $VehicleCode;
                $lmCarDriv['CarTypeCode']   = $CarTypeCode;
                $lmCarDriv['IsDefault']     = 'N';

                DB::table('LMDBM.dbo.lmCarDriv')->insert($lmCarDriv);
    
                $dateRange  = $req->InsureStart_End_Date;
                $dateRange  = explode(' - ',$dateRange);

                $Insure_date_start      = $dateRange[0];
                $Stamp_date_start       = Carbon::createFromFormat('d/m/Y', $Insure_date_start)->format('Y-m-d');
        
                $Insure_date_end        = $dateRange[1];
                $Stamp_date_end         = Carbon::createFromFormat('d/m/Y', $Insure_date_end)->format('Y-m-d');
    
                $PolicyInsureNo         = $req->PolicyInsureNo;
                $InsureTypeCode         = $this->explodeVal($req->InsureTypeCode);
                $InsureCompCode         = $this->explodeVal($req->InsureCompCode);
                $InsureCap              = $req->InsureCap;
                $InsureValue            = $req->InsureValue;
    
                $CarInsure['VehicleCode']       = $VehicleCode;
                $CarInsure['PolicyInsureNo']    = $PolicyInsureNo;
                $CarInsure['InsureTypeCode']    = $InsureTypeCode;
                $CarInsure['InsureCompCode']    = $InsureCompCode;
                $CarInsure['DateStart']         = $Stamp_date_start;
                $CarInsure['DateEnd']           = $Stamp_date_end;
                $CarInsure['InsureCap']         = $InsureCap;
                $CarInsure['InsureValue']       = $PolicyInsureNo;
    
                DB::table('LMDBM.dbo.lmCarInsure')->insert($CarInsure);
    
                $dateRange2  = $req->Insure_Form_Start_End_Date;
                $dateRange2  = explode(' - ',$dateRange2);
        
                $Insure_Form_date_start      = $dateRange2[0];
                $Stamp_Form_date_start       = Carbon::createFromFormat('d/m/Y', $Insure_Form_date_start)->format('Y-m-d');
        
                $Insure_Form_date_end        = $dateRange2[1];
                $Stamp_Form_date_end         = Carbon::createFromFormat('d/m/Y', $Insure_Form_date_end)->format('Y-m-d');
    
                $FormulateNO                    = $req->FormulateNO;
                $InsureTypeCode_Form            = $this->explodeVal($req->InsureTypeCode_Form);
                $InsureCompCode_Form            = $this->explodeVal($req->InsureCompCode_Form);
                $FormulateValue                 = $req->FormulateValue;
    
                $CarFormInsure['VehicleCode']       = $VehicleCode;
                $CarFormInsure['FormulateNO']       = $FormulateNO;
                $CarFormInsure['InsureTypeCode']    = $InsureTypeCode_Form;
                $CarFormInsure['InsureCompCode']    = $InsureCompCode_Form;
                $CarFormInsure['DateStart']         = $Stamp_date_start;
                $CarFormInsure['DateEnd']           = $Stamp_date_end;
                $CarFormInsure['FormulateValue']    = $FormulateValue;
    
                DB::table('LMDBM.dbo.lmCarForm')->insert($CarFormInsure);
    
                $dateRange3  = $req->CarFin_Start_End_Date;
                $dateRange3  = explode(' - ',$dateRange3);
    
                $CarFin_date_start      = $dateRange3[0];
                $CarFin_date_start       = Carbon::createFromFormat('d/m/Y', $CarFin_date_start)->format('Y-m-d');
        
                $CarFin_date_end        = $dateRange3[1];
                $CarFin_date_end         = Carbon::createFromFormat('d/m/Y', $CarFin_date_end)->format('Y-m-d');
    
                $FinanceCode                = $this->explodeVal($req->FinanceCode);
                $ContractNo                 = $req->ContractNo;
                $CarValue                   = $req->CarValue;
                $CarTotalValue              = $req->CarTotalValue;
                $CarDownValue               = $req->CarDownValue;
                $CarInterest                = $req->CarInterest;
                $CarLoanValue               = $req->CarLoanValue;
                $CarInstallmentValue        = $req->CarInstallmentValue;
                $CarInstallment             = $req->CarInstallment;
                $CarInstallmentLeft         = $req->CarInstallmentLeft;
                $CarBalanceValue            = $req->CarBalanceValue;
                $CarFin_Start_End_Date      = $req->CarFin_Start_End_Date;
                $CarFinRemark               = $req->CarFinRemark;
                $CarFinStatus               = $req->CarFinStatus;
    
                $CarFin['VehicleCode']          = $VehicleCode;
                $CarFin['FinanceCode']          = $FinanceCode;
                $CarFin['ContractNo']           = $ContractNo;
                $CarFin['CarValue']             = $CarValue;
                $CarFin['CarTotalValue']        = $CarTotalValue;
                $CarFin['CarDownValue']         = $CarDownValue;
                $CarFin['CarInterest']          = $CarInterest;
                $CarFin['CarLoanValue']         = $CarLoanValue;
                $CarFin['CarInstallmentValue']  = $CarInstallmentValue;
                $CarFin['CarInstallment']       = $CarInstallment;
                $CarFin['CarInstallmentLeft']   = $CarInstallmentLeft;
                $CarFin['CarBalanceValue']      = $CarBalanceValue;
                $CarFin['DateStart']            = $CarFin_date_start;
                $CarFin['DateEnd']              = $CarFin_date_end;
                $CarFin['DateRangeI']           = "25 - 10";
                $CarFin['DateRangeII']          = "11 - 24";
                $CarFin['Remark']               = $CarFinRemark;
                $CarFin['Status']               = $CarFinStatus;
    
                DB::table('LMDBM.dbo.lmCarFinan')->insert($CarFin);
    
    
                $dateRange4  = $req->CompCar_Start_End_Date;
                $dateRange4  = explode(' - ',$dateRange4);
    
                $CompCar_date_start      = $dateRange4[0];
                $CompCar_date_start       = Carbon::createFromFormat('d/m/Y', $CompCar_date_start)->format('Y-m-d');
        
                $CompCar_date_end        = $dateRange4[1];
                $CompCar_date_end         = Carbon::createFromFormat('d/m/Y', $CompCar_date_end)->format('Y-m-d');
    
                $CompCarValue               = $req->CompCarValue;
                $CompCarTotal               = $req->CompCarTotal;
                $CompDownValue              = $req->CompDownValue;
                $CompCarInterest            = $req->CompCarInterest;
                $CompCarLoan                = $req->CompCarLoan;
                $CompInstallmentValue       = $req->CompInstallmentValue;
                $CompInstallment            = $req->CompInstallment;
                $CompInstallmentLeft        = $req->CompInstallmentLeft;
                $CompCarBalance             = $req->CompCarBalance;
                $CompCar_Remark             = $req->CompCar_Remark;
                $CarCompStatus              = $req->CarCompStatus;
    
                $CarCom['VehicleCode']          = $VehicleCode;
                $CarCom['CompCarValue']         = $CompCarValue;
                $CarCom['CompCarTotal']         = $CompCarTotal;
                $CarCom['CompDownValue']        = $CompDownValue;
                $CarCom['CompCarInterest']      = $CompCarInterest;
                $CarCom['CompInstallmentValue'] = $CompInstallmentValue;
                $CarCom['CompCarInstallment']   = $CompInstallment;
                $CarCom['CompInstallmentLeft']  = $CompInstallmentLeft;
                $CarCom['CompCarBalance']       = $CompCarBalance;
                $CarCom['Status']               = $CarCompStatus;
                $CarCom['DateStart']            = $CompCar_date_start;
                $CarCom['DateEnd']              = $CompCar_date_end;
                $CarCom['DateRangeI']           = "25 - 10";
                $CarCom['DateRangeII']          = "11 - 24";
                $CarCom['Remark']               = $CompCar_Remark;
    
                DB::table('LMDBM.dbo.lmCarComp')->insert($CarCom);
                DB::commit();
    
                return 'success';
            }else{
                // dd($req->DataEdit);
                if($req->DataEdit != 'null'){
                    $dataJson       = json_encode($req->DataEdit,true);
                    $VehicleCode    = $req->VehicleCode;
                    $Port           = Auth::user()->EmpCode;
                    
                    $CheckRow = DB::table('LMSTemp_EditlmCarDriv')
                                ->where([
                                    'status_confirm'=>'N',
                                    'vehicleCode' => $VehicleCode
                                ])
                                ->count();
                    if($CheckRow == 0){
                        $temp['vehicleCode']    = $VehicleCode;
                        $temp['created_time']   = now();
                        $temp['created_by']     = $Port;
                        $temp['data_update']    = $dataJson;
        
                        DB::table('LMSTemp_EditlmCarDriv')->insert($temp);
        
                        DB::commit();
        
                        return 'success';
                    }else{

                        return 'added';
                    }
                }else{
                    return 'dataNull';
                }
            }
           
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
        
    }

    private function explodeVal($text){
        $Array_text = explode(":",$text);
        return $Array_text[0];
    }
}
