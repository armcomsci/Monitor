<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class confirmImgCustController extends Controller
{
    public function index(Request $req){
        $Custname = '';
        $status = 'N';

        if(isset($req->CustName) || isset($req->status) ){
            $Custname = $req->CustName;
            $status   = $req->status;
        }

        $imgCust = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchConfirmGPS');
        if($status != 'A'){
            $imgCust  = $imgCust->where('Flag_st',$status);
        }
        if($Custname != ""){
            $imgCust  = $imgCust->where('CustName','LIKE',"%$Custname%");
        }
        $imgCust  = $imgCust->whereNotNull('ImgPath');
        $imgCust  = $imgCust->get();

        return view('confirmImgCust',compact('imgCust','status','Custname'));
    }

    public function confirm(Request $req){ 
        try {
            $custid = $req->custid;
            $shipno = $req->shipno;
            $status = $req->status;
            
            $CustName =  DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchConfirmGPS')->where([
                'CustID' => $custid,
                'ShipListNo' => $shipno
            ])->first();
            
            $row =  DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchConfirmGPS')->where([
                        'CustID' => $custid,
                        'ShipListNo' => $shipno
                    ]);
            if($status == "N"){
                $row =  $row->delete();
            }elseif($status == "Y"){
                $updated['Flag_st_2'] = "Y";
                $updated['Appv2Date'] = now();
                $updated['Appv2Name'] = Auth::user()->Fullname;
                $row =  $row->update($updated);
            }

            DB::beginTransaction();

            $logSave['EmpCode']         = Auth::user()->EmpCode;
            $logSave['StatusConfirm']   = $status;
            $logSave['CustID']          = $custid;
            $logSave['ShipListNo']      = $shipno;
            $logSave['CustImg']         = $CustName->ImgPath;
            $logSave['CustName']        = $CustName->CustName;
            $logSave['lat']             = $CustName->Latitude;
            $logSave['long']            = $CustName->Longitude;
            $logSave['DatetimeConfirm'] = now();

            DB::table('LMSLog_ConfirmImgCust')->insert($logSave);

            // if($status == "Y"){
            //     $detail = "ยืนยันพิกัดร้าน $CustName->CustName พิกัด : $CustName->Latitude,$CustName->Longitude";
            // }elseif($status == "R"){
            //     $detail = "ปฏิเสธพิกัดร้าน $CustName->CustName พิกัด : $CustName->Latitude,$CustName->Longitude";
            // }

            // $this->saveLogEvent($detail,10);
            DB::commit();

            return "success";
            
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
