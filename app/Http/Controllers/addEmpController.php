<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class addEmpController extends Controller
{
    //
    public function index(){

        $Cardriv    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                    ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                    ->where('lmCarDriv.IsDefault','Y')
                    ->join('dbwins_new1.dbo.EMTransp AS EMTransp','lmEmpDriv.TranspID','EMTransp.TranspID')
                    ->distinct()
                    ->select('EMTransp.TranspID')
                    ->selectRaw("REPLACE(EMTransp.TranspName, 'ขนส่งโดย', '') AS TranspName")
                    ->get();

        return view('addEmp',compact('Cardriv'));
    }

    public function filter_emp(Request $req){

        $WorkDate   = $req->workdate;
        $CarSize    = $req->car_size;

        $WorkDate   = date_create($WorkDate);
        $WorkDate   = date_format($WorkDate,"Y-m-d H:i:s");

        $EmpDriv    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.TranspID',$CarSize)
                        ->orderBy('lmEmpDriv.EmpDriverCode')
                        ->get();


        $data['Emp']   = json_decode($EmpDriv,true);

        $Send_date = DB::table('LMSDBM.dbo.LMSwork_date As lmWorkDate')
                        ->join('LMDBM.dbo.lmEmpDriv AS lmEmpDriv','lmWorkDate.EmpDriverCode','lmEmpDriv.EmpDriverCode')  
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmWorkDate.Status','lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmWorkDate.SentDate',$WorkDate)
                        ->where('lmWorkDate.TranspID',$CarSize)
                        ->get();

        $Send_arr  = array();
        if(count($Send_date) != ""){
            $data['Send'] = json_decode($Send_date,true);
        }
        
        $data = json_encode($data,true);

        return $data;
    }

    public function save(Request $req){
        DB::beginTransaction();

        $Empcode    = $req->Empcode;

        $DateWork   = $req->DateWork; 
        $DateWork   = date_create($DateWork);
        $DateWork   = date_format($DateWork,"Y-m-d H:i:s");

        $UserId     = Auth::user()->EmpCode;

        foreach ($Empcode as $key => $value) {

            $EmpDriv  = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.EmpDriverCode',$value)
                        ->first();

            $Data_work['SentDate']          = $DateWork;
            $Data_work['EmpDriverCode']     = $EmpDriv->EmpDriverCode;
            $Data_work['CarLicense']        = $EmpDriv->VehicleCode;
            $Data_work['CarTypeCode']       = $EmpDriv->CarTypeCode;
            $Data_work['TranspID']          = $EmpDriv->TranspID;
            $Data_work['Status']            = 'Y';
            $Data_work['creater']           = $UserId;
            $Data_work['created_at']        = now();
            $Data_work['updater']           = $UserId;
            $Data_work['updated_at']        = now();

            try {
                $count =  DB::table('LMSDBM.dbo.LMSwork_date')->insert($Data_work);
                if($count > 1){
                    DB::rollBack();
                    return "fail1";
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                return $th;
            }     
        }
        DB::commit();
        return $Empcode;
    }

    public function change_status(Request $req){
 
        DB::beginTransaction();
        $EmpCode = $req->Empcode;
        $Status  = $req->Status;

        $DateWork   = $req->DateWork;
        $WorkDate   = date_create($DateWork);
        $WorkDate   = date_format($WorkDate,"Ymd");

        try{
            $count = DB::table('LMSDBM.dbo.LMSwork_date')
                            ->where('EmpDriverCode',$EmpCode)
                            ->whereRaw("CONVERT(varchar,SentDate,112) = '".$WorkDate."'")->update(['Status'=>$Status]);
            if($count > 1){
                DB::rollBack();
                return "fail";
            }
        }catch (\Throwable $th) {
            DB::rollBack();
            return "fail";
        }    

        DB::commit();
        return "success";
    }

}
