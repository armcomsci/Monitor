<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\scoreboardController;

class changeTimeController extends Controller
{
    //
    public function index(){
        $Port    = Auth::user()->EmpCode;

        $Container = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                        ->join('LMSJob_Contain as job','m_contain.ContainerNo','job.ContainerNo')
                        ->leftjoin('LMDBM.dbo.lmEmpContainers as contain','m_contain.ContainerNo','contain.ContainerNo')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','m_contain.Empcode','Driv.EmpDriverCode')
                        ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->leftjoin('LMSJobLog_Contain as job_transfer','contain.ContainerNo','job_transfer.ContainerNo')
                        ->select('m_contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','Driv.EmpDriverCode','contain.created_at','contain.updated_at','transp.CarType','job_transfer.Status as status_transfer','m_contain.ConfirmFlag as flag_job','m_contain.ConfirmDate','contain.flag as flag_exit')
                        ->distinct()
                        ->where([
                                'CDriv.IsDefault'=>'Y',
                                // 'contain.flag'=>'N',
                                'job.EmpCode'=>$Port,
                                'job.status'=>'N'
                            ])
                        ->whereRaw('(contain.created_at is null OR contain.updated_at is null)')
                        ->get();
                            // dd($Container);
        return view('changeTimeEmp',compact('Container'));
    }

    public function save(Request $req){
        DB::beginTransaction();

        $flag       = $req->flag;
        $timesave   = $req->TimeSave;
        $container  = $req->container;
        try {


            $dataEmp    =  DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                            ->join('LMSJob_Contain as job','m_contain.ContainerNo','job.ContainerNo')
                            ->leftjoin('LMDBM.dbo.lmEmpContainers as contain','m_contain.ContainerNo','contain.ContainerNo')
                            ->join('LMDBM.dbo.lmEmpDriv as Driv','m_contain.Empcode','Driv.EmpDriverCode')
                            ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                            ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                            ->join('LMDBM.dbo.lmCarType as CDType','CDriv.CarTypeCode','CDType.CarTypeCode')
                            ->leftjoin('LMSJobLog_Contain as job_transfer','contain.ContainerNo','job_transfer.ContainerNo')
                            ->select('m_contain.ContainerNo','m_contain.EmpID','Driv.EmpDriverCode','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','CDType.CarTypeName','job_transfer.Status as status_transfer','m_contain.ConfirmFlag as flag_job','m_contain.ConfirmDate','contain.flag as flag_exit')
                            ->distinct()
                            ->where([
                                    'CDriv.IsDefault'=>'Y',
                                    // 'contain.flag'=>'N',
                                    'job.status'=>'N',
                                    'm_contain.ContainerNo' => $container
                            ])
                            ->first();
             
            $CheckContainer = DB::table('LMDBM.dbo.lmEmpContainers')->where('ContainerNo',$container)->count();
            
            if($flag == "join"){
                $status = "Y";
                $textLog = "เข้า";
            }elseif($flag == "exit"){
                $status = "N";
                $textLog = "ออก";
            }

            $dataCon['EmpID']       = $dataEmp->EmpID;
            $dataCon['EmpCode']     = $dataEmp->EmpDriverCode;
            $dataCon['ContainerNo'] = $container;
            $dataCon['flag']        = $status;
            if($CheckContainer == 0){
                $dataCon['created_at'] = $timesave;
               
            }elseif($CheckContainer >= 1){
                $dataCon['updated_at'] = $timesave;
            }

            $lmEmp = DB::table('LMDBM.dbo.lmEmpContainers');
            if($CheckContainer == 0){
                $lmEmp = $lmEmp->insert($dataCon);
            }elseif($CheckContainer >= 1){
                $lmEmp = $lmEmp->where('ContainerNo',$container);
                $lmEmp = $lmEmp->update($dataCon);
            }

            $date       = date_create($timesave);
            $Stamp_date = date_format($date,'Ymd');
            $Stamp_Time = date_format($date,'H:m');
            $CurentDate = date('Ymd');

            // dd($CurentDate,$Stamp_date);
            if($CurentDate == $Stamp_date){
                $tableLm = "LMDBM.dbo.lmEmpTran_Now";
            }else{
                $tableLm = "LMDBM.dbo.lmEmpTran_Sent";
            }

            $MaxID = DB::table('LMDBM.dbo.lmEmpTran_Now')->select('EmpTranID')->orderByDesc('EmpTranID')->first();
            $MaxID = $MaxID->EmpTranID+1;

            $empRun = DB::table($tableLm);
            if($CurentDate != $Stamp_date){
                $empRun = $empRun->where('Stamp_Date',$Stamp_date);
            }            
            $empRun = $empRun->where('EmpDriverCode',$dataEmp->EmpDriverCode);
            $empRun = $empRun->where('Past','<>','C');
            $empRun = $empRun->count();
            $empRun = $empRun+1;
            // dd($empRun,$dataEmp->EmpDriverCode);
            $tranNow['EmpTranID']           = $MaxID;
            $tranNow['EmpDriverCode']       = $dataEmp->EmpDriverCode;
            $tranNow['EmpDriverFullName']   = $dataEmp->EmpDriverName." ".$dataEmp->EmpDriverlastName;
            $tranNow['CarTypeCode']         = $dataEmp->CarType;
            $tranNow['CarTypeName']         = $dataEmp->CarTypeName;
            $tranNow['VehicleCode']         = $dataEmp->VehicleCode;
            $tranNow['Stamp_Date']          = $Stamp_date;
            if($flag == "join"){
                $tranNow['Time_Entry']      = $Stamp_Time;
                $tranNow['Time_Work']       = $Stamp_Time;
                $tranNow['EmpStamp_Times']      = $empRun;
            }elseif($flag == "exit"){
                $timeWork = DB::table($tableLm)->select('Time_Entry','Time_Work')->where('Contain_Default',$container)->first();
                // dd($container);
                $tranNow['Time_Entry']      = $timeWork->Time_Entry;
                $tranNow['Time_Work']       = $timeWork->Time_Work;
                $tranNow['Time_exit']       = $Stamp_Time;
            }
          
            $tranNow['Contain_Default']     = $container;
            $tranNow['Past']                = "N";   

            $CheckContain = DB::table($tableLm)->where('Contain_Default',$container)->count();

            $updateRun = DB::table($tableLm);
            if($CurentDate != $Stamp_date){
                $updateRun->where('Stamp_Date',$Stamp_date);
            }    
            $updateRun = $updateRun->where('EmpDriverCode',$dataEmp->EmpDriverCode);
            $updateRun = $updateRun->where('Past','<>','C');
            $updateRun = $updateRun->update(['Past'=>'Y']);

            if($CheckContain >= 1){
                DB::table($tableLm)->where('Contain_Default',$container)->update($tranNow);
            }else{
                DB::table($tableLm)->where('Contain_Default',$container)->delete();

                DB::table($tableLm)->insert($tranNow);
            }

            $ScoreUpdate['EmpCode']     = Auth::user()->EmpCode;
            $ScoreUpdate['Score']       = '-0.5';
            $ScoreUpdate['ContainerNo'] = $container;
            $ScoreUpdate['DateTime']    = now();

            $CheckScore = DB::table('LMSScoreJob')->insert($ScoreUpdate);
            
            $logStampTime['EmpID']          =   $dataEmp->EmpID;
            $logStampTime['EmpDriveName']   =   $dataEmp->EmpDriverName." ".$dataEmp->EmpDriverlastName;
            $logStampTime['EmpDriveCode']   =   $dataEmp->EmpDriverCode;
            $logStampTime['Port']           =   Auth::user()->EmpCode;
            $logStampTime['TimeStamp']      =   $timesave;
            $logStampTime['Status']         =   $flag;
            $logStampTime['Created_time']   =   now();
            $logStampTime['ContainerNo']    =   $container;

            DB::table('LMSLog_stamptime')->insert($logStampTime);

            $scoreboard     =  new scoreboardController();
            $detail         = "เลขตู้ :".$container." แก้ไขเวลา".$textLog." ของคุณ".$dataEmp->EmpDriverName." ".$dataEmp->EmpDriverlastName." เป็นเวลา : ".$timesave;
            $code           = "09";
            $scoreboard->saveLogEvent($detail,$code);

            DB::commit();

            return "success";

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    } 
    
}
