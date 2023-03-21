<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use Carbon\Carbon;

class scoreboardController extends Controller
{
    public function index() {

        $Port    = Auth::user()->EmpCode;

        $Container = DB::table('LMDBM.dbo.lmEmpContainers as contain')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                        ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->join('LMSJob_Contain as job','contain.ContainerNo','job.ContainerNo')
                        ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType')
                        ->where([
                                'CDriv.IsDefault'=>'Y',
                                // 'contain.flag'=>'N',
                                'contain.Port'=>$Port,
                                'job.status'=>'N'
                            ])
                        ->get();

        
        
        $Login    =  new LoginController();
        $CountJob =  $Login->CheckPort(1);

        $AllJob   =  DB::table('LMSJob_Contain')
                        ->where('EmpCode',$Port)
                        ->where('status','N')
                        ->count();

        $JobTransFer = $this->dataJobTransfer();
        $JobTransFer = count($JobTransFer);
                        
        return view('monitor',compact('Container','CountJob','AllJob','JobTransFer'));
    }

    public function dataDt($Container){
        $dataHd     = DB::connection('sqlsrv')->table('LMDBM.dbo.lmEmpContainers as contain')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','CDriv.VehicleCode','Driv.EmpDriverTel')
                        ->where('CDriv.IsDefault','Y')
                        // ->where('contain.flag','N')
                        ->where('contain.ContainerNo',$Container)
                        ->first();

        $VehicleCode = str_replace(array('-',' '),'',$dataHd->VehicleCode);

        $Data['Drive']        = $dataHd;

        $Data['Route']        = DB::connection('sqlsrv')->table('TMSDBM.dbo.nTMConTain_route')->where('ContainerNo',$Container)->OrderBy('TranIndex','ASC')->get();
  
        $Data['location']     = DB::connection('sqlsrv')->table('LMSLogGps_temp')->where('vehicle_id',$VehicleCode)->first();

        $Data['Order']        =  DB::connection('sqlsrv_2')
                                ->table('nlmMatchContain_dt')
                                ->select('CustID','CustName','Flag_st')
                                ->selectRaw('SUM(GoodQty) as SumQty')
                                ->where('ContainerNO',$Container)
                                ->groupBy('CustID','CustName','Flag_st')
                                ->get();

        $Data['Remark']       = DB::table('LMSRemark')->where(['ContainerNo'=>$Container,'Status'=>'Y'])->orderby('Datetime','ASC')->get();

        $selectAddBill        = DB::table('LMDBM.dbo.lmAddBill_Now_hd')->select('ContainerNO','Addbill_Time')->where('ContainerNO',$Container);

        $selectAddBill_Temp   = DB::table('LMDBM.dbo.lmAddBill_Temp_hd')->select('ContainerNO','Addbill_Time')->where('ContainerNO',$Container);


        $selectAddBill_Ref   = DB::table('LMDBM.dbo.lmAddBill_Ref_hd')
                                ->select('ContainerNO','Addbill_Time')
                                ->union($selectAddBill)
                                ->union($selectAddBill_Temp)
                                ->where('ContainerNO',$Container)
                                ->first();

        $Data['AddBill']     = $selectAddBill_Ref;

        return response()->json($Data, 200);
    }

    public function dataJob(){

        $Curent_date = date('Ymd');
        if(date('w')  == "1"){
            $Ago_date    = date('Ymd',strtotime(' -2 day'));
        }else{
            $Ago_date    = date('Ymd',strtotime(' -1 day'));
        }

        $Container  = DB::table('LMDBM.dbo.lmEmpContainers as contain')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->select('contain.ContainerNo','contain.created_at','contain.updated_at','Driv.EmpDriverName','Driv.EmpDriverlastName','CDriv.VehicleCode','Driv.EmpDriverTel')
                        ->distinct()
                        ->where('CDriv.IsDefault','Y')
                        ->whereNull('contain.Port')
                        ->whereRaw("CONVERT(varchar,contain.updated_at,112) BETWEEN '$Ago_date' AND '$Curent_date' ")
                        ->get();


        return response()->json($Container, 200);
    }

    public function dataOrderItem($Container){
        $Data['OrderList']     =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain_dt')
                                    ->select('GoodCode','GoodName','GoodQty','GoodUnit','Flag_st')
                                    ->where('ContainerNO',$Container)
                                    ->get();

        $Data['CustList']     =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain_dt')
                                    ->select('CustID','CustCode','CustName','ShiptoAddr1','Flag_st','Flag_st_date')
                                    ->where('ContainerNO',$Container)
                                    ->groupBy('CustID','CustCode','CustName','ShiptoAddr1','Flag_st','Flag_st_date')
                                    ->orderBy('Flag_st_date','ASC')
                                    ->get();
        // dd($Data);
        return response()->json($Data, 200);
    }

    public function getUserLogin(){
        $Curent_date = date('Ymd');

        $userOnline  = DB::table('LMSLog_login')
                        ->join('LMSusers','LMSLog_login.EmpCode','LMSusers.EmpCode')
                        ->select('LMSLog_login.*','LMSusers.Fullname');
        // $userOnline  = $userOnline->where('LMSLog_login.Status_online','Y');
        $userOnline  = $userOnline->where('LMSLog_login.EmpCode','<>',Auth::user()->EmpCode);
        $userOnline  = $userOnline->whereRaw("CONVERT(varchar,LMSLog_login.Login_time,112) = '$Curent_date' ");
        $userOnline  = $userOnline->get();

        return response()->json($userOnline, 200);
    }

    public function getJobTransStatus(){
        $EmpCode    = Auth::user()->EmpCode;

        $data =  DB::table('LMSJobLog_Contain as log_contain')
                    ->join('LMDBM.dbo.lmEmpContainers as contain','log_contain.ContainerNo','contain.ContainerNo')
                    ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                    ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                    ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                    ->join('LMSusers as user','log_contain.SendTo','user.Empcode')
                    ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','user.Fullname','log_contain.Datetime','log_contain.Status')
                    ->where('log_contain.EmpCode',$EmpCode)
                    // ->where('log_contain.Status','W')
                    ->orderbyDesc('log_contain.Datetime')
                    ->get();
        return response()->json($data, 200);
    }

    public function getHistory(){
        $EmpCode    = Auth::user()->EmpCode;
        $dateS      = Carbon::now()->subMonth(1);
        $dateE      = Carbon::now(); 
        $log = DB::table('LMSHistoryEvent')
                ->where('EmpCode',$EmpCode)
                ->whereBetween('Datetime',[$dateS,$dateE])
                ->orderbyDesc('Datetime')
                ->get();

        return response()->json($log, 200);
    }

    public function getReceive(){
      
        $log =  $this->dataJobTransfer();

        return response()->json($log, 200);
    }

    public function dataJobTransfer(){
        $EmpCode    = Auth::user()->EmpCode;
        $data =  DB::table('LMSJobLog_Contain as log_contain')
                    ->join('LMDBM.dbo.lmEmpContainers as contain','log_contain.ContainerNo','contain.ContainerNo')
                    ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                    ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                    ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                    ->join('LMSusers as user','log_contain.Empcode','user.Empcode')
                    ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','user.Fullname','log_contain.Datetime')
                    ->where('log_contain.SendTo',$EmpCode)
                    ->where('log_contain.Status','W')
                    ->orderbyDesc('log_contain.Datetime')
                    ->get();
        return $data;
    }

    public function saveJob(Request $req){
        DB::beginTransaction();
        try {
            $container = $req->container;

            foreach ($container as $key => $value) {
                $SaveJob['ContainerNo'] = $value;
                $SaveJob['EmpCode']     = Auth::user()->EmpCode;

                $CheckIn = DB::table('LMSJob_Contain')->insert($SaveJob);
                if($CheckIn){
                    $UpdateCon['Port']          = Auth::user()->EmpCode;
                    $UpdateCon['Port_Updated']  = now();
                    DB::table('LMDBM.dbo.lmEmpContainers')->where('ContainerNo',$value)->update($UpdateCon);
                }else{
                    DB::rollback();
                }
              
            }   

            $Login    =  new LoginController();
            $CountJob =  $Login->CheckPort(1);

            $detail = "กดรับงานเพิ่ม จำนวน ".count($container)." งาน";
            $code   = "01";
            $this->saveLogEvent($detail,$code);

            $res['status']      = "success";
            $res['CountJob']    = $CountJob;

            DB::commit();
            return response()->json($res, 200);;
        }catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    // public function saveClearJob(Request $req){
    //     DB::beginTransaction();
    //     try {
    //         $container = $req->containerEmpty;
    //         // foreach ($container as $key => $value) {
    //         //     $log['EmpCode']     = Auth::user()->EmpCode;
    //         //     $log['ContainerNo'] = $value;
    //         //     $log['Status']      = "R";
    //         //     $log['Datetime']    = now();
    //         //     $this->saveLog($log);

    //         //     $UpdateCon['Port']          = NULL;
    //         //     $UpdateCon['Port_Updated']  = NULL;
    //         //     DB::table('LMDBM.dbo.lmEmpContainers')->where('ContainerNo',$value)->update($UpdateCon);

    //         //     DB::table('LMSJob_Contain')->where('ContainerNo',$value)->delete();
    //         // }

            
    //         // $detail = "คืนงาน จำนวน ".count($container)." งาน";
    //         // $code   = "02";
    //         // $this->saveLogEvent($detail,$code);

    //         // DB::commit();

    //         return "success";
    //     }catch (\Throwable $th) {
    //         DB::rollback();
    //         return $th->getMessage();
    //     }
    // }

    public function saveTransJob(Request $req){

        DB::beginTransaction();
        try {
            $container = $req->containerTrans;
            foreach ($container as $key => $value) {
                $log['EmpCode']     = Auth::user()->EmpCode;
                $log['ContainerNo'] = $value;
                $log['Status']      = "W";
                $log['SendTo']      = $req->sendto;
                $log['Datetime']    = now();
                $this->saveLog($log);

                // $UpdateCon['Port']          = $req->sendto;;
                // $UpdateCon['Port_Updated']  = now();
                // DB::table('LMDBM.dbo.lmEmpContainers')->where('ContainerNo',$value)->update($UpdateCon);

                // $updatePort['EmpCode']      = $req->sendto;
                // DB::table('LMSJob_Contain')->where('ContainerNo',$value)->update($updatePort);
            }
         

            $EmpName = DB::table('LMSusers')->select('Fullname')->where('EmpCode',$req->sendto)->first();

            $detail = "โอนงาน จำนวน".count($container)." งาน ไปยัง ".$EmpName->Fullname;
            $code   = "03";
            $log    = $this->saveLogEvent($detail,$code);

            DB::commit();
            return "success";
        }catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function saveLogEvent($detail,$code){
        // 00 = รับงานใหม่
        // 01 = รับงานเพิ่ม
        // 02 = คืนงาน
        // 03 = โอนงาน
        // 04 = เพิ่ม Remark 
        // 05 = ลบ Remark
        DB::beginTransaction();
        try {
            $data['EmpCode']    = Auth::user()->EmpCode;
            $data['Detail']     = $detail;
            $data['DetailCode'] = $code;
            $data['Datetime']   = now();

            $insert = DB::table('LMSHistoryEvent')->insert($data);
            if(!$insert){
                DB::rollback();
                return $insert;
            }
            DB::commit();
            return $insert;
        }catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function saveRemark(Request $req){
        DB::beginTransaction();
        try {
            $remark     =   $req->remark;
            $container  =   $req->container;
            
            $data['Remark']         =   $remark;
            $data['ContainerNo']    =   $container;
            $data['EmpCode']        =   Auth::user()->EmpCode;
            $data['Datetime']       =   now();
            $idRemark               =   DB::table('LMSRemark')->insertGetID($data);
            
            $detail = "เพิ่ม Remark : ".$remark." เลขตู้ ".$container;
            $code   = "04";
            $log    = $this->saveLogEvent($detail,$code);

            DB::commit();
            return $idRemark;

        }catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function clearRemark(Request $req){
        DB::beginTransaction();
        try {
            $id         = $req->id;
            $container  = $req->container;
            $text       = $req->text;

            $data['Status'] = 'N';

            DB::table('LMSRemark')->where(['ContainerNo'=>$container,'id'=>$id])->update($data);

            $detail = "ลบ Remark : ".$text." เลขตู้ ".$container;
            $code   = "05";
            $log    = $this->saveLogEvent($detail,$code);

            DB::commit();
            return "success";
        }catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function saveLog($data){
       $log =  DB::table('LMSJobLog_Contain')->insert($data);
       
       return $log;
    }
}
