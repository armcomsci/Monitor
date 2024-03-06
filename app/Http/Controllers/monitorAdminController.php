<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class monitorAdminController extends Controller
{
    public function index(){

        $Users = DB::table('LMSusers')->select('EmpCode','Fullname')->where('type',1)->get();

        return view('indexAdmin',compact('Users'));
    }

    public function findjob(Request $req){

        $findjob = $req->findjob;
        $port    = $req->port;
        
        $Container = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                        ->join('LMSJob_Contain as job','m_contain.ContainerNo','job.ContainerNo')
                        ->join('LMSusers as user','job.EmpCode','user.EmpCode')
                        ->leftjoin('LMDBM.dbo.lmEmpContainers as contain','m_contain.ContainerNo','contain.ContainerNo')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','m_contain.Empcode','Driv.EmpDriverCode')
                        ->join('DTDBM.dbo.vEMTransp as transp','Driv.TranspID','transp.TranspID')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->leftjoin('LMSJobLog_Contain as job_transfer','contain.ContainerNo','job_transfer.ContainerNo')
                        ->select('m_contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverTel','Driv.EmpDriverCode','CDriv.VehicleCode','contain.created_at','contain.updated_at','transp.CarType','job_transfer.Status as status_transfer','m_contain.ConfirmFlag as flag_job','contain.flag as flag_exit','user.Fullname')
                        ->distinct()
                        ->where([
                                'CDriv.IsDefault'=>'Y',
                                // 'contain.flag'=>'N',
                                // 'job.EmpCode'=>$port,
                                'job.status'=>'N'
                        ]);
        if($findjob != ""){
            $Container = $Container->where(function ($query) use ($findjob) {
                $query->orWhere('Driv.EmpDriverName','LIKE',"%$findjob%")
                        ->orWhere('m_contain.ContainerNo','LIKE',"%$findjob%")
                        ->orWhere('Driv.EmpDriverlastName','LIKE',"%$findjob%")
                        ->orWhere('CDriv.VehicleCode','LIKE',"%$findjob%");
            });
        }
        if($port != ""){
            $Container = $Container->where('job.EmpCode',$port);
        }
        $Container  =   $Container->get();
        return view('dataJobPort',compact('Container'));
    }

    public function detail($ContainerNo){
        $dataHd     = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                        ->join('LMSJob_Contain as job','m_contain.ContainerNo','job.ContainerNo')
                        ->leftjoin('LMDBM.dbo.lmEmpContainers as contain','m_contain.ContainerNo','contain.ContainerNo')
                        ->join('LMDBM.dbo.lmEmpDriv as Driv','m_contain.Empcode','Driv.EmpDriverCode')
                        ->join('LMDBM.dbo.lmCarDriv as CDriv','Driv.EmpDriverCode','CDriv.EmpDriverCode')
                        ->select('m_contain.ContainerNo','Driv.EmpDriverName','Driv.EmpDriverlastName','Driv.EmpDriverCode','CDriv.VehicleCode','Driv.EmpDriverTel','Driv.EmpGroupCode')
                        ->selectRaw("(select top(1) status from LMSJobLog_Contain where ContainerNo = '$ContainerNo' order by Datetime desc) as statusTrans ")
                        ->where('CDriv.IsDefault','Y')
                        // ->where('contain.flag','N')
                        ->where('m_contain.ContainerNo',$ContainerNo)
                        ->first();

        $VehicleCode = str_replace(array('-',' '),'',$dataHd->VehicleCode);

        $Data['Drive']        = $dataHd;

        $Data['Route']        = DB::connection('sqlsrv')->table('TMSDBM.dbo.nTMConTain_route')->where('ContainerNo',$ContainerNo)->OrderBy('TranIndex','ASC')->get();

        $Data['location']     = DB::connection('sqlsrv')->table('LMSLogGps_temp')->where('vehicle_id',$VehicleCode)->first();

        $Data['Order']        =  DB::connection('sqlsrv_2')
                                ->table('nlmMatchContain_dt')
                                ->select('CustID','CustName','CustCode','CustID','Flag_st')
                                ->selectRaw('SUM(GoodQty) as SumQty')
                                ->where('ContainerNO',$ContainerNo)
                                ->groupBy('CustID','CustName','CustCode','CustID','Flag_st')
                                ->get();

        $Data['CustList']     =  DB::connection('sqlsrv_2')
                                ->table('nlmMatchContain_dt as contain_dt')
                                ->select('contain_dt.CustID','contain_dt.CustCode','contain_dt.CustName','contain_dt.ShiptoAddr1','contain_dt.Flag_st','contain_dt.Flag_st_date')
                                ->where('contain_dt.ContainerNO',$ContainerNo)
                                ->distinct()
                                ->orderBy('contain_dt.Flag_st_date','ASC')
                                ->get();

        $Data['Remark']       = DB::table('LMSRemark')->where(['ContainerNo'=>$ContainerNo,'Status'=>'Y'])->orderby('Datetime','ASC')->get();

        $selectAddBill        = DB::table('LMDBM.dbo.lmAddBill_Now_hd')->select('ContainerNO','Addbill_Time')->where('ContainerNO',$ContainerNo);

        $selectAddBill_Temp   = DB::table('LMDBM.dbo.lmAddBill_Temp_hd')->select('ContainerNO','Addbill_Time')->where('ContainerNO',$ContainerNo);


        $selectAddBill_Ref   = DB::table('LMDBM.dbo.lmAddBill_Ref_hd')
                        ->select('ContainerNO','Addbill_Time')
                        ->union($selectAddBill)
                        ->union($selectAddBill_Temp)
                        ->where('ContainerNO',$ContainerNo)
                        ->first();

        $Data['AddBill']     = $selectAddBill_Ref;

        return view('dataJobDetail',compact('Data'));
    }

    public function dataItem(Request $req){
        $custid     = $req->custid;
        $container  = $req->container;

        $data       =   DB::connection('sqlsrv_2')
                            ->table('nlmMatchContain_dt')
                            ->select('CustName','GoodCode','GoodName','GoodQty','GoodUnit','Flag_st')
                            ->where(['ContainerNO'=>$container,'CustID'=>$custid])
                            ->get();

        return response()->json($data, 200);
    }
}
