<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class addCarController extends Controller
{
    //
    public function index(){
        $CarType = DB::select("SELECT        TranspID, TranspName, TranspGroup
        FROM            (SELECT        TranspID, LTRIM(REPLACE(TranspName, 'ขนส่งโดย', '')) AS TranspName, CASE WHEN TranspNameEng = '' THEN 'เจ.ที.แพ็ค ออฟ ฟู้ดส์' ELSE 'บริษัท ' + TranspNameEng END AS TranspGroup
                                  FROM            DTDBM.dbo.EMTransp
                                  WHERE        (Remark = 1)) AS X
        WHERE        (TranspGroup IN ('เจ.ที.แพ็ค ออฟ ฟู้ดส์'))
        ORDER BY TranspID");
       
        return view('addCar',compact('CarType'));
    }

    public function GetEvent(Request $req){
        $StartDay = $req->StartDay;

        $GetEvent = DB::table('LMSTranSend_Amount')->where('SendDate',$StartDay)->get();

        return response()->json($GetEvent);
    }

    public function event(){
        $data = DB::table('LMSTranSend_Amount')
                    ->join('DTDBM.dbo.EMTransp as tranSp','LMSTranSend_Amount.TranspId','tranSp.TranspID')
                    ->select('LMSTranSend_Amount.SendDate','LMSTranSend_Amount.Amount','LMSTranSend_Amount.TranspId','tranSp.TranspCode','tranSp.TranspName')
                    ->get();
        return response()->json($data);
    }

    public function saveDate(Request $req){
        DB::beginTransaction();

        $TranSp = array_filter($req->TranSp);
        $StartDay = $req->sendDate;
        try {
            $GetEvent = DB::table('LMSTranSend_Amount')->where('SendDate',$StartDay)->count();

            if($GetEvent > 1){
                DB::table('LMSTranSend_Amount')->where('SendDate',$StartDay)->delete();
            }

            foreach ($TranSp as $key => $value) {
                if($value > 0){
                    $saveDate['TranspId']       = $key;
                    $saveDate['SendDate']       = $StartDay;
                    $saveDate['Amount']         = $value;
                    $saveDate['Update_by']      = Auth::user()->EmpCode;
                    $saveDate['Update_time']    = now();

                    $Insert = DB::table('LMSTranSend_Amount')->insert($saveDate);   

                    if(!$Insert){
                        DB::rollback();
                    }    
                }
            }
            DB::commit();

            return "success";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
