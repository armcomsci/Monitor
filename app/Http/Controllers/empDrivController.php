<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\scoreboardController;

class empDrivController extends Controller
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
                        ->select('m_contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','Driv.EmpDriverCode','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','job_transfer.Status as status_transfer','m_contain.ConfirmFlag as flag_job','m_contain.ConfirmDate','contain.flag as flag_exit')
                        ->distinct()
                        ->where([
                                'CDriv.IsDefault'=>'Y',
                                // 'contain.flag'=>'N',
                                'job.EmpCode'=>$Port,
                                'job.status'=>'N'
                            ])
                        ->whereNull('contain.ContainerNo')
                        ->get();

        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->orderBy('lmEmpDriv.EmpDriverCode')
                        ->get();

        return view('changeEmpDriv',compact('Container','EmpName'));
    }
    
    public function save(Request $req){
        
        $Container  = $req->container;
        $NewEmp     = $req->NewEmpDriv;
        $Port       = Auth::user()->EmpCode;

        try {
            DB::beginTransaction();

            $DataEmp   =  DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->join('dbwins_new1.dbo.EMEmp as EMEmp','lmEmpDriv.EmpDriverCode','EMEmp.EmpCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','EMEmp.EmpID')
                        ->selectRaw("lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.EmpDriverCode',$NewEmp)
                        ->first();

            $oldEmp    = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain')
                        ->select('EmpID','EmpCode','EmpName')
                        ->where('ContainerNo',$Container)
                        ->first();

            $LogChange['oldDriveCode'] =  $oldEmp->EmpCode;
            $LogChange['oldDriveName'] =  $oldEmp->EmpName;
            $LogChange['newDriveCode'] =  $DataEmp->EmpDriverCode;
            $LogChange['newDriveName'] =  $DataEmp->EmpDriverName;
            $LogChange['port']         =  $Port;
            $LogChange['updated_time'] =  now();

            DB::table('LMSLog_changeDrive')->insert($LogChange);

            $scoreboard    =  new scoreboardController();
            $detail = "เลขตู้ :".$Container." เปลี่ยนคนรถจาก : ".$oldEmp->EmpName." เป็น ".$DataEmp->EmpDriverName;
            $code   = "08";
            $scoreboard->saveLogEvent($detail,$code);

            $EmpDriverCode = $DataEmp->EmpDriverCode;
            $VehicleCode   = $DataEmp->VehicleCode;
            $EmpID         = $DataEmp->EmpID;
            $EmpDriverName = $DataEmp->EmpDriverName;

            DB::table('TMSDBM.dbo.nTMConTain_hd')->where('ContainerNo',$Container)->update(['EmpDriverCode'=>$EmpDriverCode]);
            
            DB::commit();

            $updateCon['EmpID']     = $EmpID;
            $updateCon['EmpCode']   = $EmpDriverCode;
            $updateCon['EmpName']   = $EmpDriverName;
            DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain')->where('ContainerNo',$Container)->update($updateCon);

            return "success";

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
       
    }
}
