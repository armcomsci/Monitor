<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class cancelContainController extends Controller
{
    //
    public function index(){
        $Port    = Auth::user()->EmpCode;

        $Container = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                        ->join('TMSDBM.dbo.nTMConTain_hd as nTMConTain_hd','m_contain.ContainerNo','nTMConTain_hd.ContainerNo')
                        ->join('LMSJob_Contain as job','m_contain.ContainerNo','job.ContainerNo')
                        ->leftjoin('LMDBM.dbo.lmEmpContainers as contain','m_contain.ContainerNo','contain.ContainerNo')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','m_contain.Empcode','Driv.EmpDriverCode')
                        ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->leftjoin('LMSJobLog_Contain as job_transfer','contain.ContainerNo','job_transfer.ContainerNo')
                        ->select('m_contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','job_transfer.Status as status_transfer','m_contain.ConfirmFlag as flag_job','m_contain.ConfirmDate','contain.flag as flag_exit')
                        ->distinct()
                        ->where([
                                'nTMConTain_hd.Flag_st'=>'J',
                                'm_contain.ConfirmFlag' => 'Y',
                                'CDriv.IsDefault'=>'Y',
                                'contain.flag'=>'N',
                                'job.EmpCode'=>$Port,
                                'job.status'=>'N'
                            ])
                        ->whereRaw('(contain.created_at is null OR contain.updated_at is null)')
                        ->get();
        // dd($Container);
        return view('cancelContain',compact('Container'));
    }

    public function confirmReturn(Request $req){
        DB::beginTransaction();
        try {
            $nlmContain  = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain')
                    ->where('ContainerNO',$req->container)
                    ->update(['ConfirmFlag'=>'N']);

            $ConTain_hd = DB::table('TMSDBM.dbo.nTMConTain_hd')
                    ->where('ContainerNO',$req->container)
                    ->update(['Flag_st'=>'X']);

            DB::commit();
            return "success";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
