<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OilConfigController extends Controller
{
    //
    public function configOil(){
        $OilSet = DB::table('LMDBM.dbo.lmOilValue as lmOilValue')
                    ->join('LMDBM.dbo.lmOilType as lmOilType','lmOilValue.OilTypeCode','lmOilType.OilTypeCode')
                    ->join('LMDBM.dbo.lmOilComp as lmOilComp','lmOilType.OilCompCode','lmOilComp.OilCompCode')
                    ->select('lmOilValue.*','lmOilType.OilTypeName','lmOilComp.OilCompName')
                    ->orderByDesc('lmOilValue.OilDate')
                    ->get();

        $OilType = DB::table('LMDBM.dbo.lmOilType as lmOilType')->get();

        return view('settingOil.configOil',compact('OilSet','OilType'));
    }

    public function configOilGetComp(Request $req){
        $OilTypeCode = $req->value;
        

        $OilComp    = DB::table('LMDBM.dbo.lmOilType as lmOilType')
                        ->join('LMDBM.dbo.lmOilComp as lmOilComp','lmOilType.OilCompCode','lmOilComp.OilCompCode')
                        ->select('lmOilComp.OilCompName')
                        ->where('lmOilType.OilTypeCode',$OilTypeCode)->first();

        return response()->json($OilComp,200);
    }

    public function configOilSave(Request $req){

        $oilType    = $req->oilType;
        $OilDate    = $req->OilDate;
        $OilPrice   = $req->OilPrice;
        $EmpCode    = Auth::user()->EmpCode;

        try {

                $lmOilValue['OilTypeCode']    = $oilType;
                $lmOilValue['OilDate']        = $OilDate;
                $lmOilValue['OilPrice']       = $OilPrice;
                $lmOilValue['SetDate']        = date('Y-m-d');
                $lmOilValue['SetTime']        = date('H:i');
                $lmOilValue['SetEmpID']       = '1';
                $lmOilValue['SetEmpCode']     = $EmpCode;
                
                DB::table('LMDBM.dbo.lmOilValue')->insert($lmOilValue);
    
                 return 'success';
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
