<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class generalController extends Controller
{
    //
    public function profileEmp(){
        $lmEmp = DB::table('LMDBM.dbo.lmEmpGrop')->get();

        return view('general.profileEmp',compact('lmEmp'));
    }

    public function profileEmpSave(Request $req){
        $groupcode      = $req->groupcode;
        $groupname      = $req->groupname;
        $groupremark    = $req->groupremark;
        $type = $req->type;
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmEmpGrop')->where('EmpGroupCode',$groupcode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $EmpGroup['EmpGroupCode'] = $groupcode;
                    $EmpGroup['EmpGroupName'] = $groupname;
                    $EmpGroup['Remark']       = $groupremark;
                    
                    DB::table('LMDBM.dbo.lmEmpGrop')->insert($EmpGroup);
    
                    return 'success';
                }
            }elseif($type == 1){
                $EmpGroup['EmpGroupName'] = $groupname;
                $EmpGroup['Remark']       = $groupremark;

                DB::table('LMDBM.dbo.lmEmpGrop')->where('EmpGroupCode',$groupcode)->update($EmpGroup);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileEmpGet(Request $req){
        $groupcode = $req->id;
        $data = DB::table('LMDBM.dbo.lmEmpGrop')->where('EmpGroupCode',$groupcode)->first();

        return response()->json($data,200);
    }

    public function profileEmpDel(Request $req){
        $groupcode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmEmpGrop')->where('EmpGroupCode',$groupcode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileCar(){
        return view('general.profileCar');
    }

    public function ProfileCarType(){
        $CarBand = DB::table('LMDBM.dbo.lmCarBand')->get();

        return view('general.profileCarType',compact('CarBand'));
    }

    public function ProfileCarTypeSave(Request $req){
        $carbrandcode      = $req->carbrandcode;
        $carbrandname      = $req->carbrandname;
        $carbrandremark    = $req->carbrandremark;
        $type = $req->type;
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmCarBand')->where('CarBrandCode',$carbrandcode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $CarBrand['CarBrandCode']           = $carbrandcode;
                    $CarBrand['CarBrandName']           = $carbrandname;
                    $CarBrand['CarBrandRemark']         = $carbrandremark;
                    
                    DB::table('LMDBM.dbo.lmCarBand')->insert($CarBrand);
    
                    return 'success';
                }
            }elseif($type == 1){
                $CarBrand['CarBrandName']           = $carbrandname;
                $CarBrand['CarBrandRemark']         = $carbrandremark;

                DB::table('LMDBM.dbo.lmCarBand')->where('CarBrandCode',$carbrandcode)->update($CarBrand);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileCarTypeGet(Request $req){
        $carbrandcode = $req->id;

        $data = DB::table('LMDBM.dbo.lmCarBand')->where('CarBrandCode',$carbrandcode)->first();

        return response()->json($data,200);
    }

    public function profileCarDel(Request $req){
        $carbrandcode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmCarBand')->where('CarBrandCode',$carbrandcode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileCarGroup(){
        $CarGroup = DB::table('LMDBM.dbo.lmCarSerie as lmCarSerie')
                    ->join('LMDBM.dbo.lmCarBand as lmCarBand','lmCarSerie.CarBrandCode','lmCarBand.CarBrandCode')
                    ->select('lmCarSerie.*','lmCarBand.CarBrandName')
                    ->get();

        return view('general.profileCarGroup',compact('CarGroup'));
    }

    public function profileCarGroupSave(Request $req){
        $carseriecode       = $req->carseriecode;
        $carseriename       = $req->carseriename;
        $carbrandcode       = $req->carbrandcode;
        $carserieremark     = $req->carserieremark;

        $type = $req->type;
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmCarSerie')->where('CarSerieCode',$carseriecode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $CarGroup['CarSerieCode']           = $carseriecode;
                    $CarGroup['CarSerieName']           = $carseriename;
                    $CarGroup['CarBrandCode']           = $carbrandcode;
                    $CarGroup['CarSerieRemark']         = $carserieremark;
                    
                    DB::table('LMDBM.dbo.lmCarSerie')->insert($CarGroup);
    
                    return 'success';
                }
            }elseif($type == 1){
                $CarGroup['CarSerieName']           = $carseriename;
                $CarGroup['CarBrandCode']           = $carbrandcode;
                $CarGroup['CarSerieRemark']         = $carserieremark;

                DB::table('LMDBM.dbo.lmCarSerie')->where('CarSerieCode',$carseriecode)->update($CarGroup);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileCarGroupGet(Request $req){
        $CarSerieCode = $req->id;

        $data = DB::table('LMDBM.dbo.lmCarSerie')->where('CarSerieCode',$CarSerieCode)->first();

        return response()->json($data,200);
    }

    public function profileCarGroupDel(Request $req){
        $CarSerieCode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmCarSerie')->where('CarSerieCode',$CarSerieCode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileRateCar(){
        return view('general.ProfileRateCar');
    }

    public function ProfileFinance(){
        $finance = DB::table('LMDBM.dbo.lmFinComp')->get();
        return view('general.ProfileFinance',compact('finance'));
    }

    public function profileFinanceGet(Request $req){
        $FinanceCode = $req->id;
        $data = DB::table('LMDBM.dbo.lmFinComp')->where('FinanceCode',$FinanceCode)->first();

        return response()->json($data,200);
    }

    public function profileFinanceSave(Request $req){
        $financecode        = $req->financecode;
        $financename        = $req->financename;
        $financetel         = $req->financetel;
        $financeaddr        = $req->financeaddr;
        $financeremark      = $req->financeremark;

        $type = $req->type;
        // dd($req);
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmFinComp')->where('FinanceCode',$financecode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $Finance['FinanceCode']           = $financecode;
                    $Finance['FinanceName']           = $financename;
                    $Finance['FinanceTel']            = $financetel;
                    $Finance['FinanceAddr']       = $financeaddr;
                    $Finance['FinanceRemark']         = $financeremark;
                    
                    DB::table('LMDBM.dbo.lmFinComp')->insert($Finance);
    
                    return 'success';
                }
            }elseif($type == 1){
                $Finance['FinanceName']           = $financename;
                $Finance['FinanceTel']            = $financetel;
                $Finance['FinanceAddr']           = $financeaddr;
                $Finance['FinanceRemark']         = $financeremark;

                DB::table('LMDBM.dbo.lmFinComp')->where('FinanceCode',$financecode)->update($Finance);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileFinanceDel(Request $req){
        $FinanceCode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmFinComp')->where('FinanceCode',$FinanceCode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileInsurance(){
        $insurer = DB::table('LMDBM.dbo.lmInsComp')->get();

        return view('general.ProfileInsurance',compact('insurer'));
    }

    public function profileInsuranceGet(Request $req){
        $InsurerCode = $req->id;
        $data = DB::table('LMDBM.dbo.lmInsComp')->where('InsureCompCode',$InsurerCode)->first();

        return response()->json($data,200);
    }

    public function profileInsuranceSave(Request $req){
        $insurercode        = $req->insurercode;
        $insurername        = $req->insurername;
        $insurertel         = $req->insurertel;
        $insureraddr        = $req->insureraddr;
        $insurerfax         = $req->insurerfax;
        $insurerremark      = $req->insurerremark;

        $type = $req->type;
        // dd($req);
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmInsComp')->where('InsurerCode',$insurercode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $Insurer['InsurerCode']           = $insurercode;
                    $Insurer['InsurerName']           = $insurername;
                    $Insurer['InsurerTel']            = $insurertel;
                    $Insurer['InsurerAddress1']       = $insureraddr;
                    $Insurer['InsurerFax']            = $insurerfax;
                    $Insurer['InsurerRemark']         = $insurerremark;
                    
                    DB::table('LMDBM.dbo.lmInsComp')->insert($Insurer);
    
                    return 'success';
                }
            }elseif($type == 1){
                    $Insurer['InsurerName']           = $insurername;
                    $Insurer['InsurerTel']            = $insurertel;
                    $Insurer['InsurerAddress1']       = $insureraddr;
                    $Insurer['InsurerFax']            = $insurerfax;
                    $Insurer['InsurerRemark']         = $insurerremark;

                DB::table('LMDBM.dbo.lmInsComp')->where('InsurerCode',$insurercode)->update($Insurer);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileInsuranceDel(Request $req){
        $InsurerCode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmInsComp')->where('InsureCompCode',$InsurerCode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileInsuranceType(){
        $insurerType = DB::table('LMDBM.dbo.lmInsType')->get();

        return view('general.ProfileInsuranceType',compact('insurerType'));
    }

    public function profileInsuranceTypeGet(Request $req){
        $InsurerTypeCode = $req->id;
        $data = DB::table('LMDBM.dbo.lmInsType')->where('InsureTypeCode',$InsurerTypeCode)->first();

        return response()->json($data,200);
    }

    public function profileInsuranceTypeSave(Request $req){
        $insurertypecode      = $req->insurertypecode;
        $insurertypename      = $req->insurertypename;
        $insurertyperemark    = $req->insurertyperemark;
        $type = $req->type;
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmInsType')->where('InsurerTypeCode',$insurertypecode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $InsurerType['InsureTypeCode']            = $insurertypecode;
                    $InsurerType['InsureTypeName']            = $insurertypename;
                    $InsurerType['InsureTypeRemark']          = $insurertyperemark;
                    
                    DB::table('LMDBM.dbo.lmInsType')->insert($InsurerType);
    
                    return 'success';
                }
            }elseif($type == 1){
                $InsurerType['InsureTypeName']         = $insurertypename;
                $InsurerType['InsureTypeRemark']       = $insurertyperemark;

                DB::table('LMDBM.dbo.lmInsType')->where('InsureTypeCode',$insurertypecode)->update($InsurerType);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileInsuranceTypeDel(Request $req){
        // dd($req);
        $InsurerTypeCode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmInsType')->where('InsurerTypeCode',$InsurerTypeCode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileRouteProductCode(){
        $AreaZone = DB::table('LMDBM.dbo.lmAreaZone')->get();
        return view('general.ProfileRouteProductCode',compact('AreaZone'));
    }

    public function ProfileRouteProduct(){
        $AreaZone = DB::table('LMDBM.dbo.lmAreaZone')->get();

        $lmBkkMart_tm   =   DB::table('LMDBM.dbo.lmBkkMart_tm')->where('Remark','New Delivery Area')->get();

        return view('general.ProfileRouteProduct',compact('AreaZone','lmBkkMart_tm'));
    }

    public function ProfileRouteProductData(Request $req){
        $AreaCode = $req->AreaCode;

        $AreaRoute = DB::table('LMDBM.dbo.lmAreaRoute as lmAreaRoute')
                    ->join('LMDBM.dbo.lmBkkMart_tm as lmBkkMart_tm','lmAreaRoute.MarketID','lmBkkMart_tm.MarketID')
                    ->where('lmAreaRoute.MarketID','<>','0')
                    ->where('lmAreaRoute.AreaCode',$AreaCode)
                    ->get();

        $AreaRoute2 = DB::table('LMDBM.dbo.lmAreaRoute as lmAreaRoute')
                    ->leftjoin('LMDBM.dbo.lmCenTran_tm as lmCenTran_tm ','lmAreaRoute.TranCenID','lmCenTran_tm.TranCenID')
                    ->select('lmAreaRoute.TranCenID', 'lmCenTran_tm.TranCenName', 'lmCenTran_tm.remark')
                    ->where('lmAreaRoute.TranCenID','<>','0')
                    ->where('lmAreaRoute.AreaCode',$AreaCode)
                    ->get();


        return view('general.ProfileRouteProductData',compact('AreaRoute','AreaRoute2'));
    }

    public function ProfileRouteProduct_Del_Mark(Request $req){
        $id = $req->id;
        $areaCode = $req->AreaCode;

        DB::table('LMDBM.dbo.lmAreaRoute')
            ->where('AreaCode',$areaCode)
            ->where('MarketID',$id)
            ->delete();

        return 'success';
    }

    public function ProfileRouteProduct_Del_Tran(Request $req){
        $id = $req->id;
        $AreaCode = $req->AreaCode;

        DB::table('LMDBM.dbo.lmAreaRoute')
            ->where('AreaCode',$areaCode)
            ->where('TranCenID',$id)
            ->delete();

        return 'success';
    }

    public function GetlmCenTran(){
        $AreaCode[] = $_GET['AreaCode'];
        

        $TransID  = DB::table('LMDBM.dbo.lmAreaRoute')->select('TranCenID')->where('AreaCode',$AreaCode)->first();

        $GetData  = DB::table('LMDBM.dbo.lmCenTran_tm as lmCenTran_tm')
                        ->join('LMDBM.dbo.lmGrpCent_tm as lmGrpCent_tm', 'lmCenTran_tm.TranCenID', '=', 'lmGrpCent_tm.TranCenID')
                        ->join('LMDBM.dbo.lmGrpTran_tm as lmGrpTran_tm', 'lmGrpCent_tm.TranGroupID', '=', 'lmGrpTran_tm.TranGroupID')
                        ->join('LMDBM.dbo.lmGrpZone_tm as lmGrpZone_tm', 'lmGrpZone_tm.TranGroupID', '=', 'lmGrpTran_tm.TranGroupID')
                        ->join('LMDBM.dbo.lmTrnZone_tm as lmTrnZone_tm', 'lmTrnZone_tm.ZoneID', '=', 'lmGrpZone_tm.ZoneID')
                        ->select(
                            'lmCenTran_tm.TranCenID', 
                            'lmCenTran_tm.TranCenName', 
                            'lmGrpTran_tm.TranGroupID', 
                            'lmGrpTran_tm.TranGroupName', 
                            'lmTrnZone_tm.ZoneID', 
                            'lmTrnZone_tm.ZoneName', 
                            'lmCenTran_tm.IndexCen', 
                            'lmCenTran_tm.remark'
                        )
                    ->whereNotNull('lmCenTran_tm.TranCenID')
                    ->whereNotIn('lmCenTran_tm.TranCenID',$AreaCode)
                    ->get();
        $AR_Code = $_GET['AreaCode'];
        return view('general.ProfileRouteTran_tm_modal',compact('GetData','AR_Code'))->render();
    }

    public function ProfileRouteProductSave(Request $req){
        $MarketID = $req->MarketID;
        $AreaCode = $req->AreaCode;

        foreach ($MarketID as $key => $value) {
            $data['AreaCode'] = $AreaCode;
            $data['MarketID'] = $value;
            $data['TranCenID'] = 0;
            
            DB::table('LMDBM.dbo.lmAreaRoute')->insert($data);
        }

        return 'success';
    }

    public function ProfileRouteTransSave(Request $req){
        // dd($req);
        $TranCenID = $req->TranCenID;
        $AreaCode    = $req->AreaCode;

        foreach ($TranCenID as $key => $value) {
            $data['AreaCode'] = $AreaCode;
            $data['MarketID'] = 0;
            $data['TranCenID'] = $value;
            
            DB::table('LMDBM.dbo.lmAreaRoute')->insert($data);
        }
        return 'success';
    }

    public function ProfileCalTrans(){
        return view('general.profileCalTrans');
    }

    public function ProfileCalTransGet(Request $req){
        $FormulaCode = $req->FormulaCode;
        
        $Data = DB::table('LMDBM.dbo.lmSetFormula')->where('FormulaCode',$FormulaCode)->first();

        return response()->json($Data,200);
    }

    public function ProfileCalTransSave(Request $req){

        $FormulaCode = $req->FormulaCode_h;
        $CheckCode = DB::table('LMDBM.dbo.lmSetFormula')->where('FormulaCode',$FormulaCode)->count();
        
        $data['Var_StartValue']     = $req->Var_StartValue;
        $data['var_OilUsePerKM']    = $req->var_OilUsePerKM;
        $data['Var_Cond_Capacity']  = $req->Var_Cond_Capacity;
        $data['Var_Cond_Weight']    = $req->Var_Cond_Weight;
        $data['Var_Cond_Distance1'] = $req->Var_Cond_Distance1;
        $data['Var_Cond_Distance2'] = $req->Var_Cond_Distance2;
        $data['Var_CustCompute']    = $req->Var_CustCompute;
        $data['Var_Cond_Constant']  = $req->Var_Cond_Constant;
        $data['Var_LowCost']        = $req->Var_LowCost;
        $data['Var_ForInsure']      = $req->Var_ForInsure;
        $data['Var_Savings']        = $req->Var_Savings;

        if(isset($req->AreaColumn_h)){
            $data['st_AreaColumn']        = $req->AreaColumn_h;
        }

        if(isset($req->FormulaDetail_h)){
            $data['FormulaDetail']        = $req->FormulaDetail_h;
        }

        try {
            if($CheckCode >= 1){
                DB::table('LMDBM.dbo.lmSetFormula')->where('FormulaCode',$FormulaCode)->update($data);
            }elseif($CheckCode == 0){
                $data['FormulaCode'] = $FormulaCode;
                DB::table('LMDBM.dbo.lmSetFormula')->insert($data);
            }
            return "success";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileSetCalTrans(){
        return view('general.profileSetCalTrans');
    }

    public function ProfileSetCalTransGet(Request $req){
        $FormulaCode = $req->FormulaCode;
        
        $Data = DB::table('LMDBM.dbo.lmSetFormula_co')->where('FormulaCode',$FormulaCode)->first();

        return response()->json($Data,200);
    }

    public function ProfileSetCalTransSave(Request $req){
        $FormulaCode = $req->FormulaCode_h;
        $CheckCode = DB::table('LMDBM.dbo.lmSetFormula_co')->where('FormulaCode',$FormulaCode)->count();

        $SetFormula = DB::table('LMDBM.dbo.lmSetFormula')->where('FormulaCode',$FormulaCode)->first();
        
        $data['Var_StartValue']     = $req->Var_StartValue_co;
        $data['var_OilUserPerKM']   = $SetFormula->var_OilUsePerKM_co;
        $data['Var_Cond_Capacity']  = $req->Var_Cond_Capacity_co;
        $data['Var_Cond_Weight']    = $req->Var_Cond_Weight_co;
        $data['Var_Cond_Distance1'] = $req->Var_Cond_Distance1_co;
        $data['Var_Cond_Distance2'] = $req->Var_Cond_Distance2_co;

        $data['Var_Cond_Constant']  = $SetFormula->Var_Cond_Constant;
        $data['Var_LowCost']        = $SetFormula->Var_LowCost;
        $data['Var_ForInsure']      = $SetFormula->Var_ForInsure;
        $data['Var_Savings']        = $SetFormula->Var_Savings;

        if(isset($req->AreaColumn_h)){
            $data['st_AreaColumn']        = $req->AreaColumn_co_h;
        }

        if(isset($req->FormulaDetail_h)){
            $data['FormulaDetail']        = $req->FormulaDetail_co_h;
        }

        try {
            if($CheckCode >= 1){
                DB::table('LMDBM.dbo.lmSetFormula_co')->where('FormulaCode',$FormulaCode)->update($data);
            }elseif($CheckCode == 0){
                $data['FormulaCode'] = $FormulaCode;
                DB::table('LMDBM.dbo.lmSetFormula_co')->insert($data);
            }
            return "success";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileTransport(){
        return view('general.ProfileTransport');
    }

    public function ProfilePay(){
        $Reduct = DB::table('LMDBM.dbo.lmAddFund_ExpType')->orderByDesc('ExpTypeID')->get();
        return view('general.ProfilePay',compact('Reduct'));
    }

    public function profilePayGet(Request $req){
        $ExpTypeID = $req->id;
        $data = DB::table('LMDBM.dbo.lmAddFund_ExpType')->where('ExpTypeID',$ExpTypeID)->first();

        return response()->json($data,200);
    }

    public function profilePaySave(Request $req){
        $ExpTypeDesc        = $req->ExpTypeDesc;
        $type = $req->type;
        // dd($req);
        try {
            if($type == 0){
                    $lastID = DB::table('LMDBM.dbo.lmAddFund_ExpType')->select('ExpTypeID')->orderByDesc('ExpTypeID')->first();

                    $IncRed['ExpTypeID']            = $lastID->ExpTypeID+1;
                    $IncRed['ExpTypeDesc']          = $ExpTypeDesc;
                    $IncRed['ExpTypeUse']           = 'Y';
                    $IncRed['CalculateVAT_st']      = 'Y';

                    DB::table('LMDBM.dbo.lmAddFund_ExpType')->insert($IncRed);
    
                    return 'success';
                
            }elseif($type > 1){
                $IncRed['ExpTypeDesc']          = $ExpTypeDesc;

                DB::table('LMDBM.dbo.lmAddFund_ExpType')->where('ExpTypeID',$type)->update($IncRed);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profilePayDel(Request $req){
        $ExpTypeID = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmAddFund_ExpType')->where('ExpTypeID',$ExpTypeID)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profilePayChangeST(Request $req){
        $ExpTypeID = $req->id;
        $Status    = $req->status;
        try {
            $data = DB::table('LMDBM.dbo.lmAddFund_ExpType')->where('ExpTypeID',$ExpTypeID)->update(['ExpTypeUse'=>$Status]);
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profilePayChangeVat(Request $req){
        $ExpTypeID = $req->id;
        $Status    = $req->status;
        try {
            $data = DB::table('LMDBM.dbo.lmAddFund_ExpType')->where('ExpTypeID',$ExpTypeID)->update(['CalculateVAT_st'=>$Status]);
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileFund(){
        $DedType = DB::table('LMDBM.dbo.lmAddFund_DedType')->orderByDesc('DedTypeID')->get();
        return view('general.ProfileFund',compact('DedType'));
    }

    public function profileFundGet(Request $req){
        $DedTypeID = $req->id;
        $data = DB::table('LMDBM.dbo.lmAddFund_DedType')->where('DedTypeID',$DedTypeID)->first();

        return response()->json($data,200);
    }

    public function profileFundSave(Request $req){
        $DedTypeDesc        = $req->DedTypeDesc;
        $type = $req->type;
        // dd($req);
        try {
            if($type == 0){
                    $lastID = DB::table('LMDBM.dbo.lmAddFund_DedType')->select('DedTypeID')->orderByDesc('DedTypeID')->first();

                    $DedType['DedTypeID']            = $lastID->DedTypeID+1;
                    $DedType['DedTypeDesc']          = $DedTypeDesc;
                    $DedType['DedTypeUse']           = 'Y';
                    $DedType['CalculateVAT_st']      = 'Y';

                    DB::table('LMDBM.dbo.lmAddFund_DedType')->insert($DedType);
    
                    return 'success';
                
            }elseif($type > 1){
                $DedType['DedTypeDesc']          = $DedTypeDesc;

                DB::table('LMDBM.dbo.lmAddFund_DedType')->where('DedTypeID',$type)->update($DedType);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileFundDel(Request $req){
        $DedTypeID = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmAddFund_DedType')->where('DedTypeID',$DedTypeID)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileFundChangeST(Request $req){
        $DedTypeID = $req->id;
        $Status    = $req->status;
        try {
            $data = DB::table('LMDBM.dbo.lmAddFund_DedType')->where('DedTypeID',$DedTypeID)->update(['DedTypeUse'=>$Status]);
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileFundChangeVat(Request $req){
        $DedTypeID = $req->id;
        $Status    = $req->status;
        try {
            $data = DB::table('LMDBM.dbo.lmAddFund_DedType')->where('DedTypeID',$DedTypeID)->update(['CalculateVAT_st'=>$Status]);
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function ProfileTypeWithDraw(){
        return view('general.ProfileTypeWithDraw');
    }

    public function ProfileTypeOil(){
        $OilType = DB::table('LMDBM.dbo.lmOilType as lmOilType')
                ->join('LMDBM.dbo.lmOilComp as lmOilComp','lmOilType.OilCompCode','lmOilComp.OilCompCode')
                ->select('lmOilType.*','lmOilComp.OilCompName')
                ->get();

        return view('general.ProfileTypeOil',compact('OilType'));
    }

    public function profileOilGet(Request $req){
        $OilTypeCode = $req->id;

        $OilType = DB::table('LMDBM.dbo.lmOilType')
                    ->where('OilTypeCode',$OilTypeCode)
                    ->first();

        return response()->json($OilType,200);
    }

    public function profileOilSave(Request $req){
        $oiltypecode       = $req->oiltypecode;
        $oiltypename       = $req->oiltypename;
        $oilcompcode       = $req->oilcompcode;
        $oilcompremark     = $req->oilcompremark;

        $type = $req->type;
        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmOilType')->where('OilTypeCode',$oiltypecode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $OilType['OilTypeCode']           = $oiltypecode;
                    $OilType['OilTypeName']           = $oiltypename;
                    $OilType['OilCompCode']           = $oilcompcode;
                    $OilType['OilTypeRemark']         = $oilcompremark;
                    
                    DB::table('LMDBM.dbo.lmOilType')->insert($OilType);
    
                    return 'success';
                }
            }elseif($type == 1){
                $OilType['OilTypeName']               = $oiltypename;
                    $OilType['OilCompCode']           = $oilcompcode;
                    $OilType['OilTypeRemark']         = $oilcompremark;

                DB::table('LMDBM.dbo.lmOilType')->where('OilTypeCode',$oiltypecode)->update($OilType);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function profileOilDel(Request $req){
        $OilTypeCode = $req->id;
        try {
            $data = DB::table('LMDBM.dbo.lmOilType')->where('OilTypeCode',$OilTypeCode)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
