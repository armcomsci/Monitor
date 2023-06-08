<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    //
    public function EmpDriver(){
        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                            ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                            ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                            ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                            ->where('lmCarDriv.IsDefault','Y')
                            ->orderBy('lmEmpDriv.EmpDriverCode')
                            ->get();
    
        return view('reportEmp',compact('EmpName'));
    }

    public function find(Request $req){
        $dateRange = $req->dateRange;     
        $empcode   = $req->empcode;
        $carSize   = $req->carSize;

        $dateRange  = explode(' - ',$dateRange);
        
        $date_start       = $dateRange[0];
        $Stamp_date_start = Carbon::createFromFormat('d/m/Y', $date_start)->format('Ymd');

        $date_end       = $dateRange[1];
        $Stamp_date_end = Carbon::createFromFormat('d/m/Y', $date_end)->format('Ymd');

        // dd(empty($empcode),$empcode);

        $selectEmpNow   = DB::table('LMDBM.dbo.lmEmpTran_Now as sentNow')
                            ->select('EmpDriverCode','EmpDriverFullName','Stamp_date','VehicleCode','CarTypeCode')
                            ->selectRaw('(select top(1) EmpStamp_Times from  LMDBM.dbo.lmEmpTran_Now where  EmpDriverCode = sentNow.EmpDriverCode and Stamp_Date = sentNow.Stamp_Date ORDER BY EmpStamp_Times DESC) as EmpRun');
        if(!empty($empcode)){
            $selectEmpNow = $selectEmpNow->whereIn('sentNow.EmpDriverCode',$empcode);
        }
        if(!empty($carSize)){
            $selectEmpNow = $selectEmpNow->whereIn('sentNow.CarTypeCode',$carSize);
        }
        $selectEmpNow = $selectEmpNow->where('sentNow.Stamp_date','>=',$Stamp_date_start)
                            ->where('sentNow.Stamp_date','<=',$Stamp_date_end)
                            ->distinct();
                // ->orderby('sent.Stamp_Date','ASC');
            
        $EmpTran =  DB::table('LMDBM.dbo.lmEmpTran_Sent as tran_sent')
                ->select('EmpDriverCode','EmpDriverFullName','Stamp_date','VehicleCode','CarTypeCode')
                ->selectRaw('(select top(1) EmpStamp_Times from  LMDBM.dbo.lmEmpTran_Sent where  EmpDriverCode = tran_sent.EmpDriverCode and Stamp_Date = tran_sent.Stamp_Date ORDER BY LMDBM.dbo.lmEmpTran_Sent.EmpStamp_Times DESC) as EmpRun')
                ->distinct();

        if(!empty($empcode)){
            $EmpTran = $EmpTran->whereIn('tran_sent.EmpDriverCode',$empcode);
        } 
        if(!empty($carSize)){
            $EmpTran = $EmpTran->whereIn('tran_sent.CarTypeCode',$carSize);
        }    
        $EmpTran  = $EmpTran->where('tran_sent.Stamp_date','>=',$Stamp_date_start)
                ->where('tran_sent.Stamp_date','<=',$Stamp_date_end)
                ->union($selectEmpNow)
                ->orderby('Stamp_Date','ASC')
                ->get();

        return view('dataEmpRun',compact('EmpTran'));
    }

    public function detailRun(Request $req){
        
        $stampDate = $req->stampDate;
        $empcode   = $req->empcode;

        $selectEmpNow   = DB::table('LMDBM.dbo.lmEmpTran_Now  as sentNow')
                        ->select('Contain_Default')
                        ->where('sentNow.Stamp_Date',$stampDate)
                        ->where('sentNow.EmpDriverCode',$empcode);
        
        $Container =  DB::table('LMDBM.dbo.lmEmpTran_Sent as tran_sent')
                        ->select('Contain_Default')
                        ->where('tran_sent.Stamp_Date',$stampDate)
                        ->where('tran_sent.EmpDriverCode',$empcode)
                        ->union($selectEmpNow)
                        ->orderby('Contain_Default','ASC')
                        ->get()
                        ->toArray();
        // dd($Container);
        $AllContainer = [];
        foreach ($Container as $key => $value) {
            array_push($AllContainer,$value->Contain_Default);
        }

        $nlmContain_dt  = DB::connection('sqlsrv_2')
                            ->table('nlmMatchContain_dt')
                            ->select('GoodCode','CustName','GoodName','GoodQty','GoodUnit','Flag_st','ContainerNO')
                            ->distinct()
                            ->whereIn('ContainerNO',$AllContainer);

        $Data           =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain_dt_ref')
                                    ->select('GoodCode','CustName','GoodName','GoodQty','GoodUnit','Flag_st','ContainerNO')
                                    ->distinct()
                                    ->union($nlmContain_dt)
                                    ->whereIn('ContainerNO',$AllContainer)
                                    ->orderby('ContainerNO','ASC')
                                    ->get();
                                    
        return response()->json($Data, 200);
    }

    public function reportJobClose(){
        $Users = DB::table('LMSusers')->select('EmpCode','Fullname')->where('type',1)->get();
        return view('reportJobClose',compact('Users'));
    }

    public function findCloseJob(Request $req){
        $dateRange  = $req->dateRange;     
        $port       = $req->port;

        $dateRange  = explode(' - ',$dateRange);
        
        $date_start       = $dateRange[0];
        $Stamp_date_start = Carbon::createFromFormat('d/m/Y', $date_start)->format('Ymd');


        $date_end       = $dateRange[1];
        $Stamp_date_end = Carbon::createFromFormat('d/m/Y', $date_end)->format('Ymd');

        $jobClose = DB::table('LMSDataCloseJob')
                    ->whereRaw("CONVERT(varchar,Created_time,112) >= '$Stamp_date_start' AND CONVERT(varchar,Created_time,112) <= '$Stamp_date_end' ")
                    ->where('Created_by',$port)
                    ->orderBy('Created_time','ASC')
                    ->get();

        return view('dataEmpCloseJob',compact('jobClose'));
    }

    public function JobCloseOrderItem($Container){
        $nlmContain_dt = DB::connection('sqlsrv_2')
                        ->table('nlmMatchContain_dt')
                        ->select('GoodCode','CustName','GoodName','GoodQty','GoodUnit','Flag_st')
                        ->where('ContainerNO',$Container);

        $Data['OrderList']     =  DB::connection('sqlsrv_2')
                                    ->table('nlmMatchContain_dt_ref')
                                    ->select('GoodCode','CustName','GoodName','GoodQty','GoodUnit','Flag_st')
                                    ->union($nlmContain_dt)
                                    ->where('ContainerNO',$Container)
                                    ->get();
        // dd($Data);
        return response()->json($Data, 200);
    }
}
