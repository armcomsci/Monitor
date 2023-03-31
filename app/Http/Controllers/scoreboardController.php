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
                        ->leftjoin('LMSJobLog_Contain as job_transfer','contain.ContainerNo','job_transfer.ContainerNo')
                        ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','job_transfer.Status as status_transfer')
                        ->distinct()
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

        // $start = new Carbon('first day of last month');
        // $start->startOfMonth();
        // $end = new Carbon('last day of last month');
        // $end->endOfMonth();

        $CountCloseJob = DB::table('LMSJob_Contain')
                        ->where('EmpCode',$Port)
                        // ->whereBetween('Datetime',[$start,$end])
                        ->where('status','Y')
                        ->count();

        $Score    = DB::table('LMSScoreJob')->where('EmpCode',$Port)->sum('Score');

        $JobTransFer = $this->dataJobTransfer();
        $JobTransFer = count($JobTransFer);
                        
        return view('monitor',compact('Container','CountJob','AllJob','JobTransFer','Score','CountCloseJob'));
    }

    public function dataDt($Container){
        $dataHd     = DB::connection('sqlsrv')->table('LMDBM.dbo.lmEmpContainers as contain')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','CDriv.VehicleCode','Driv.EmpDriverTel')
                        ->selectRaw("(select top(1) status from LMSJobLog_Contain where ContainerNo = '$Container' order by Datetime desc) as statusTrans ")
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

    public function JobCloseAgo(){
        $Port    = Auth::user()->EmpCode;

        $Container = DB::table('LMDBM.dbo.lmEmpContainers as contain')
                    ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                    ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                    ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                    ->join('LMSJob_Contain as job','contain.ContainerNo','job.ContainerNo')
                    ->leftjoin('LMSJobLog_Contain as job_transfer','contain.ContainerNo','job_transfer.ContainerNo')
                    ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','job_transfer.Status as status_transfer','job.CloseTime')
                    ->distinct()
                    ->where([
                            'CDriv.IsDefault'=>'Y',
                            // 'contain.flag'=>'N',
                            'contain.Port'=>$Port,
                            'job.status'=>'Y'
                        ])
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
                                    ->table('nlmMatchContain_dt as contain_dt')
                                    ->select('contain_dt.CustID','contain_dt.CustCode','contain_dt.CustName','contain_dt.ShiptoAddr1','contain_dt.Flag_st','contain_dt.Flag_st_date')
                                    ->where('contain_dt.ContainerNO',$Container)
                                    ->distinct()
                                    ->orderBy('contain_dt.Flag_st_date','ASC')
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
                    ->where([
                        'CDriv.IsDefault'=>'Y',
                    ])
                    ->where('log_contain.SendTo',$EmpCode)
                    ->where('log_contain.Status','W')
                    ->distinct()
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
            return response()->json($res, 200);
        }catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function saveReceive(Request $req){
        DB::beginTransaction();
        try {
            $container = $req->containerRecev;
            $type      = $req->type;
            $EmpCode   =  Auth::user()->EmpCode;
            foreach ($container as $key => $value) {
                $log['ContainerNo'] = $value;
                $log['Status']      = $type;
                $log['Datetime']    = now();

                DB::table('LMSJobLog_Contain')->where('ContainerNo',$value)->update($log);

                if($type == "Y"){
 
                    $UpdateCon['Port']          = $EmpCode;
                    $UpdateCon['Port_Updated']  = now();
                    DB::table('LMDBM.dbo.lmEmpContainers')->where('ContainerNo',$value)->update($UpdateCon);

                    $updatePort['EmpCode']      = $EmpCode;
                    $updatePort['Datetime']      = now();
                    DB::table('LMSJob_Contain')->where('ContainerNo',$value)->update($updatePort);
                }
            }

            if($type == "Y"){
                $detail = "รับงานที่โอนมา จำนวน ".count($container)." งาน";
                $code   = "06";
            }elseif($type == "R"){
                $detail = "คืนงานให้กลับผู้โอน จำนวน ".count($container)." งาน";
                $code   = "02";
            }

            $this->saveLogEvent($detail,$code);

            $res['status']      = "success";
            // $res['EmpCode']     = $EmpCode;
            $res['PortName']    = Auth::user()->Fullname;
            $res['container']   = $container;
            $res['CountJob']    = count($container);

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
        // 02 = คืนงานให้กลับผู้โอน
        // 03 = โอนงานไปยัง
        // 04 = เพิ่ม Remark 
        // 05 = ลบ Remark
        // 06 = รับงานที่โอนมา
        // 07 = ปิดงาน
    
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

    public function dataCloseJob(Request $req){
        $Container  = $req->ContainerNo;
        $EmpCode    = Auth::user()->EmpCode;

        // ข้อมูลคนรถ
        $data['EmpDrive']   =   DB::table('LMDBM.dbo.lmEmpContainers as contain')
                                    ->join('LMDBM.dbo.lmEmpDriv as Driv','contain.Empcode','Driv.EmpDriverCode')
                                    ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                                    ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                                    ->select('contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','CDriv.VehicleCode','Driv.EmpDriverTel','contain.updated_at','contain.created_at','transp.CarType')
                                    ->where('contain.ContainerNo',$Container)
                                    ->first();

        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now()->startOfMonth(); 
        // ข้อมูลตู้
        $sqlnlmContainer_ref = DB::connection('sqlsrv_2')
                                ->table('nlmMatchContain_ref')
                                ->select("SentDate")
                                ->selectRaw("(select count(distinct CustID) from nlmMatchContain_dt_ref where ContainerNo = '$Container' ) as CustAll")
                                ->selectRaw("(select count(distinct CustID) from nlmMatchContain_dt_ref where ContainerNo = '$Container' and flag_st = 'Y' ) as SendSuccess")
                                ->selectRaw("(select top(1) Flag_st_date from nlmMatchContain_dt_ref where ContainerNo = '$Container' and flag_st = 'Y' order by Flag_st_date DESC ) as SendTime")
                                ->where('ContainerNo',$Container);
                                // ->whereBetween('SentDate',[$dateS,$dateE]);
                                // ->toSql();

        $data['Container']  =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain')
                                    ->select('SentDate')
                                    ->selectRaw("(select count(distinct CustID) from nlmMatchContain_dt where ContainerNo = '$Container' ) as CustAll")
                                    ->selectRaw("(select count(distinct CustID) from nlmMatchContain_dt where ContainerNo = '$Container' and flag_st = 'Y' ) as SendSuccess")
                                    ->selectRaw("(select top(1) Flag_st_date from nlmMatchContain_dt where ContainerNo = '$Container' and flag_st = 'Y' order by Flag_st_date DESC ) as SendTime")
                                    ->union($sqlnlmContainer_ref)
                                    ->where('ContainerNo',$Container)
                                    ->first();

        $data['OrderAll']        =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain_dt')
                                    // ->select('CustID','CustName','Flag_st')
                                    // ->selectRaw('SUM(GoodQty) as SumQty')
                                    ->where('ContainerNO',$Container)
                                    ->sum('GoodQty');

        $data['OrderSuc']        =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain_dt')
                                    ->where('ContainerNO',$Container)
                                    ->where('flag_st','Y')
                                    ->sum('GoodQty');
        // เวลาวางบิล
        $selectAddBill      = DB::table('LMDBM.dbo.lmAddBill_Now_hd')->select('ContainerNO','Addbill_Time')->where('ContainerNO',$Container);

        $selectAddBill_Temp = DB::table('LMDBM.dbo.lmAddBill_Temp_hd')->select('ContainerNO','Addbill_Time')->where('ContainerNO',$Container)
                                ->union($selectAddBill)
                                ->where('ContainerNO',$Container)
                                ->first();
                            
        $data['AddBill']    = $selectAddBill_Temp;


        $data['SendTo']     = DB::table('LMSJobLog_Contain')
                                ->join('LMSusers','LMSusers.EmpCode','LMSJobLog_Contain.EmpCode')
                                ->select('LMSJobLog_Contain.*','LMSusers.Fullname')
                                ->where([
                                    'LMSJobLog_Contain.ContainerNO'=>$Container
                                    ,'LMSJobLog_Contain.SendTo'=>$EmpCode
                                    ,'LMSJobLog_Contain.Status'=>'Y'
                                    ])
                                ->orderbyDesc('LMSJobLog_Contain.Datetime')
                                ->first();

        $data['Port']       =  DB::table('LMSJobLog_Contain')
                                    ->join('LMSusers','LMSusers.EmpCode','LMSJobLog_Contain.EmpCode')
                                    ->select('LMSJobLog_Contain.EmpCode','LMSusers.Fullname')
                                    ->where([
                                        'LMSJobLog_Contain.ContainerNO'=>$Container
                                        // ,'LMSJobLog_Contain.SendTo'=>$EmpCode
                                        ,'LMSJobLog_Contain.Status'=>'Y'
                                        ])
                                    ->distinct()
                                    ->get();
        
        $data['Remark']     =  DB::table('LMSRemark')->where(['ContainerNo'=>$Container,'Status'=>'Y'])->orderby('Datetime','ASC')->get();

        $res['DriveName']   =  $data['EmpDrive']->EmpDriverName." ".$data['EmpDrive']->EmpDriverlastName;
        $res['DriveTel']    =  $data['EmpDrive']->EmpDriverTel;
        $res['Container']   =  $data['EmpDrive']->ContainerNo;
        $res['VehicleCode'] =  $data['EmpDrive']->VehicleCode;
        $res['CarType']     =  $data['EmpDrive']->CarType;
        $res['JoinTime']    =  Carbon::parse($data['EmpDrive']->created_at)->format('d-m-y H:i');
        $res['ExitTime']    =  Carbon::parse($data['EmpDrive']->updated_at)->format('d-m-y H:i');
        $res['SentDate']    =  Carbon::parse($data['Container']->SentDate)->format('d-m-y');
        $res['CustAll']     =  $data['Container']->CustAll;
        $res['SendSuc']     =  $data['Container']->SendSuccess;
        $res['SumSuc']      =  $data['OrderSuc'];
        $res['SumItem']     =  $data['OrderAll'];
        $res['AddBill']     =  Carbon::parse($data['AddBill']->Addbill_Time)->format('d-m-y H:i');
        $res['Remark']      =  $data['Remark'];
        if($data['SendTo']  != ""){
            $res['SendForm']    =  $data['SendTo']->Fullname;
        }
        if($data['Port'] != ""){
            $res['PortAll']    =  $data['Port'];
        }
        $res['SendTime']    =  Carbon::parse($data['Container']->SendTime)->format('d-m-y H:i');

        $startDate =    Carbon::parse($data['EmpDrive']->updated_at)->format('ymd');
        $checkDate =    Carbon::parse($data['Container']->SentDate)->format('ymd');
        if($startDate < $checkDate){
            $startDate = Carbon::parse($data['Container']->SentDate)->format('y-m-d 05:00:00');
        }else{
            $startDate = $data['EmpDrive']->updated_at;
        }
        // dd($startDate,$data['Container']->SendTime,$data['AddBill']->Addbill_Time);
        $res['ExitToSend']  =  $this->DateCal($data['Container']->SendTime,$startDate);
        $res['ExitToBill']  =  $this->DateCal($data['Container']->SendTime,$data['AddBill']->Addbill_Time);
        $res['TimeSendAll'] =  $this->DateCal($data['AddBill']->Addbill_Time,$startDate);
        session()->put('dataClose',$res);

        return view('dataCloseJob',compact('res'));
    }

    public function CloseJob(Request $req){
        DB::beginTransaction();
        try {
            $data = session()->get('dataClose');
            // dd($data);
            $dataInsert['DriveName']        = $data['DriveName'];
            $dataInsert['DriveTel']         = $data['DriveTel'];
            $dataInsert['ContainerNo']      = $data['Container'];
            $dataInsert['VehicleCode']      = $data['VehicleCode'];
            $dataInsert['CarType']          = $data['CarType'];
            $dataInsert['JoinTime']         = $data['JoinTime'];
            $dataInsert['ExitTime']         = $data['ExitTime'];
            $dataInsert['CustSendAll']      = $data['CustAll'];
            $dataInsert['CustSendSuccess']  = $data['SendSuc'];
            $dataInsert['SumItemSend']      = $data['SumSuc'];
            $dataInsert['SumItemAll']       = $data['SumItem'];
            $dataInsert['AddBillTime']      = $data['AddBill'];
            if(count($data['Remark']) != 0){
                $text   = '';
                foreach ($data['Remark'] as $key => $value) {
                    $text .= $value->Remark.",";
                }
                $dataInsert['Remark']      =  $text;
            }
            if(isset($data['SendForm']) && $data['SendForm'] != ""){
                $data['PortSendContain'] = $data['SendForm'];
            }
            $PortEmp = 1;
            $Port[0]['EmpCode'] = Auth::user()->EmpCode;
            if(isset($data['PortAll']) && count($data['PortAll']) != "0"){
                $port   = '';
                foreach ($data['PortAll'] as $key => $value) {
                    $port .= $value->EmpCode.",";
                    if(Auth::user()->EmpCode != $value->EmpCode){
                        $Port[$PortEmp]['EmpCode'] = $value->EmpCode;
                        $PortEmp++;
                    }
                }
                $dataInsert['PortContainAll']      =  $port;
            }
            $dataInsert['TimeSend']     = $data['SendTime'];
            $dataInsert['TimeSendCust'] = $data['ExitToSend'];
            $dataInsert['TimeSendBill'] = $data['ExitToBill'];
            $dataInsert['TimeSendAll']  = $data['TimeSendAll'];
            $dataInsert['Created_time'] = now();
            $dataInsert['Created_by']   = Auth::user()->EmpCode;

            $CheckInsert =  DB::table('LMSDataCloseJob')->insert($dataInsert);
            if($CheckInsert){
                DB::table('LMSJob_Contain')
                            ->where([
                            'ContainerNo' => $data['Container'],
                            'EmpCode' =>  Auth::user()->EmpCode,
                            ])
                            ->update(['Status'=>'Y','CloseTime'=>now()]);


                $score = round(1/$PortEmp,2);
                
                foreach ($Port as $key => $value) {
                    $ScoreUpdate['EmpCode']     = $value['EmpCode'];
                    if($PortEmp == 3 && $value['EmpCode'] == Auth::user()->EmpCode){
                        $score += 0.1;
                    }
                    $ScoreUpdate['Score']       = $score;
                    $ScoreUpdate['ContainerNo'] = $data['Container'];
                    $ScoreUpdate['DateTime']    = now();

                    $CheckScore = DB::table('LMSScoreJob')->insert($ScoreUpdate);
                    if(!$CheckScore){
                        DB::rollback();
                    }
                }
                
                $res['status']  = "success";
                $res['Port']    = $Port;
                $res['Score']   = $score;

                $detail = "ปิดงานตู้ : ".$data['Container']." รับคะแนน : ".$score;
                $code   = "07";
                $log    = $this->saveLogEvent($detail,$code);
    
                DB::commit();

                
            }else{
                $res['status']  = "error";
                $res['text']    = "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
                DB::rollback();
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $res['status']  = "error";
            $res['text']    =  $th->getMessage();
        }

        return response()->json($res, 200);

    }

    public function DateCal($dateSend,$dateStart){

        $startDate  = new \DateTime($dateStart);
        $endDate    = new \DateTime($dateSend);
        $interval   = $startDate->diff($endDate);
        $days       = $interval->days;
        $hours      = $interval->h;
        $minutes    = $interval->i;

        if($days == 0){
            $text = "$hours ชั่วโมง $minutes นาที";
        }else{
            $text = "$days วัน $hours ชั่วโมง $minutes นาที";
        }

        return $text;
    }

    public function saveLog($data){
       $log =  DB::table('LMSJobLog_Contain')->insert($data);
       
       return $log;
    }
}
