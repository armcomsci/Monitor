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
        $EmpName    =  $this->GetEmpName();
        $CarGroup   =  $this->GetCarGroup();
        return view('reportEmp',compact('EmpName','CarGroup'));
    }

    public function find(Request $req){
        $dateRange = $req->dateRange;     
        $empcode   = $req->empcode;
        $carSize   = $req->carSize;
        $cargroup  = $req->cargroup;
        // dd($cargroup);

        $dateRange  = explode(' - ',$dateRange);
        
        $date_start       = $dateRange[0];
        $Stamp_date_start = Carbon::createFromFormat('d/m/Y', $date_start)->format('Ymd');

        $date_end       = $dateRange[1];
        $Stamp_date_end = Carbon::createFromFormat('d/m/Y', $date_end)->format('Ymd');

        // dd(empty($empcode),$empcode);

        $selectEmpNow   = DB::table('LMDBM.dbo.lmEmpTran_Now as sentNow')
                            ->join('dbwins_new1.dbo.EMEmp as ememp','sentNow.EmpDriverCode','ememp.EmpCode')
                            ->join('dbwins_new1.dbo.EMEmpGroup AS EMEmpGroup','ememp.EmpGroupID','EMEmpGroup.EmpGroupID')
                            ->select('sentNow.EmpDriverCode','sentNow.EmpDriverFullName','sentNow.Stamp_date','sentNow.VehicleCode','sentNow.CarTypeCode')
                            ->selectRaw('(select top(1) EmpStamp_Times from  LMDBM.dbo.lmEmpTran_Now where  EmpDriverCode = sentNow.EmpDriverCode and Stamp_Date = sentNow.Stamp_Date ORDER BY EmpStamp_Times DESC) as EmpRun');
        if(!empty($empcode)){
            $selectEmpNow = $selectEmpNow->whereIn('sentNow.EmpDriverCode',$empcode);
        }
        if(!empty($carSize)){
            $selectEmpNow = $selectEmpNow->whereIn('sentNow.CarTypeCode',$carSize);
        }
        if(!empty($cargroup)){
            $selectEmpNow = $selectEmpNow->whereIn('EMEmpGroup.EmpGroupCode',$cargroup);
        }
        $selectEmpNow = $selectEmpNow->where('sentNow.Stamp_date','>=',$Stamp_date_start)
                            ->where('sentNow.Stamp_date','<=',$Stamp_date_end)
                            ->distinct();
                // ->orderby('sent.Stamp_Date','ASC');
            
        $EmpTran =  DB::table('LMDBM.dbo.lmEmpTran_Sent as tran_sent')
                ->join('dbwins_new1.dbo.EMEmp as ememp','tran_sent.EmpDriverCode','ememp.EmpCode')
                ->join('dbwins_new1.dbo.EMEmpGroup AS EMEmpGroup','ememp.EmpGroupID','EMEmpGroup.EmpGroupID')
                ->select('tran_sent.EmpDriverCode','tran_sent.EmpDriverFullName','tran_sent.Stamp_date','tran_sent.VehicleCode','tran_sent.CarTypeCode')
                ->selectRaw('(select top(1) EmpStamp_Times from  LMDBM.dbo.lmEmpTran_Sent where  EmpDriverCode = tran_sent.EmpDriverCode and Stamp_Date = tran_sent.Stamp_Date ORDER BY LMDBM.dbo.lmEmpTran_Sent.EmpStamp_Times DESC) as EmpRun')
                ->distinct();

        if(!empty($empcode)){
            $EmpTran = $EmpTran->whereIn('tran_sent.EmpDriverCode',$empcode);
        } 
        if(!empty($carSize)){
            $EmpTran = $EmpTran->whereIn('tran_sent.CarTypeCode',$carSize);
        }    
        if(!empty($cargroup)){
            $EmpTran = $EmpTran->whereIn('EMEmpGroup.EmpGroupCode',$cargroup);
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
        $Users      = DB::table('LMSusers')->select('EmpCode','Fullname')->where('type',1)->get();
        $CarGroup   =  $this->GetCarGroup();

        return view('reportJobClose',compact('Users','CarGroup'));
    }

    public function findCloseJob(Request $req){
        $dateRange  = $req->dateRange;     
        $port       = $req->port;
        $cargroup   = $req->cargroup;

        $dateRange  = explode(' - ',$dateRange);
        
        $date_start       = $dateRange[0];
        $Stamp_date_start = Carbon::createFromFormat('d/m/Y', $date_start)->format('Ymd');

        $date_end       = $dateRange[1];
        $Stamp_date_end = Carbon::createFromFormat('d/m/Y', $date_end)->format('Ymd');

        $jobClose = DB::table('LMSDataCloseJob as jobClose')
                    ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','jobClose.VehicleCode','lmCarDriv.VehicleCode')
                    ->join('dbwins_new1.dbo.EMEmp as ememp','lmCarDriv.EmpDriverCode','ememp.EmpCode')
                    ->join('dbwins_new1.dbo.EMEmpGroup AS EMEmpGroup','ememp.EmpGroupID','EMEmpGroup.EmpGroupID')
                    ->select(
                        'jobClose.ContainerNo',
                        'jobClose.DriveName',
                        'jobClose.DriveTel',
                        'jobClose.VehicleCode',
                        'jobClose.JoinTime',
                        'jobClose.ExitTime',
                        'jobClose.SumItemSend',
                        'jobClose.SumItemAll',
                        'jobClose.CustSendSuccess',
                        'jobClose.CustSendAll',
                        'jobClose.TimeSend',
                        'jobClose.AddBillTime',
                        'jobClose.TimeSendAll',
                        'jobClose.Created_time',
                    )
                    ->whereRaw("CONVERT(varchar,Created_time,112) >= '$Stamp_date_start' AND CONVERT(varchar,Created_time,112) <= '$Stamp_date_end' ");
        if(!empty($cargroup)){
            $jobClose =  $jobClose->whereIn('EMEmpGroup.EmpGroupCode',$cargroup);
        }
        $jobClose =  $jobClose->where('jobClose.Created_by',$port)
                    ->distinct()
                    ->orderBy('jobClose.Created_time','ASC')
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

    public function reportScore(){
        return view('reportScore');
    }

    public function findScore(Request $req){
        $dateRange  = $req->dateRange;
        $dateRange  = explode(' - ',$dateRange);

        $date_start       = $dateRange[0];
        $Stamp_date_start = Carbon::createFromFormat('d/m/Y', $date_start)->format('Ymd');

        $date_end       = $dateRange[1];
        $Stamp_date_end = Carbon::createFromFormat('d/m/Y', $date_end)->format('Ymd');

        $ScoreSum =  DB::table('LMSScoreJob as score')
                    ->join('LMSusers as LmsUser','score.Empcode','LmsUser.Empcode')
                    ->select('LmsUser.Fullname','LmsUser.EmpCode')
                    ->selectRaw('SUM(score.Score) as TotalScore , DATEPART(MONTH, score.DateTime) as ScoreMonth')
                    ->whereRaw("(CONVERT(varchar, score.DateTime, 112) BETWEEN '$Stamp_date_start' AND '$Stamp_date_end'  ) ")
                    ->groupBy('LmsUser.EmpCode','LmsUser.Fullname')
                    ->groupByRaw('DATEPART(MONTH, score.DateTime)')
                    ->orderByRaw('DATEPART(MONTH, score.DateTime) ASC , TotalScore DESC')
                    ->get();

        $ScoreAll =  DB::table('LMSScoreJob as score')
                    ->join('LMSusers as LmsUser','score.Empcode','LmsUser.Empcode')
                    ->select('LmsUser.Fullname','LmsUser.EmpCode')
                    ->selectRaw('SUM(score.Score) as TotalScore')
                    ->whereRaw("(CONVERT(varchar, score.DateTime, 112) BETWEEN '$Stamp_date_start' AND '$Stamp_date_end'  ) ")
                    ->groupBy('LmsUser.EmpCode','LmsUser.Fullname')
                    ->orderBydesc('TotalScore')
                    ->get()
                    ->toArray();
        
        return view('dataScoreEmp',compact('ScoreSum','ScoreAll'));
    }

    public function reportRemark(){
        $EmpName    =  $this->GetEmpName();

        return view('reportRemark',compact('EmpName'));
    }

    public function dataRemark(Request $req){

        $dateRange  = $req->dateRange;
        $dateRange  = explode(' - ',$dateRange);

        $date_start       = $dateRange[0];
        $Stamp_date_start = Carbon::createFromFormat('d/m/Y', $date_start)->format('Ymd');

        $date_end       = $dateRange[1];
        $Stamp_date_end = Carbon::createFromFormat('d/m/Y', $date_end)->format('Ymd');

        $empcode    = $req->empcode;

        $remarks = DB::table('LMSLog_RemarkDriver as remark');
        if($empcode != ""){
            $remarks = $remarks->whereIn('remark.EmpDriverCode',$empcode);
        }
        $remarks = $remarks->select('remark.*');
        $remarks = $remarks->whereRaw("(CONVERT(varchar, remark.Datetime, 112) BETWEEN '$Stamp_date_start' AND '$Stamp_date_end'  ) ")
                    ->orderBy('remark.Datetime','DESC')
                    ->get();
        // dd($remark);
        return view('dataRemark',compact('remarks'));
    }

    public function CustConfirm(){
        $Img    = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchConfirmGPS');
        $Img    = $Img->where(['Flag_st'=>'N','Flag_st_2'=>'N']);
        $Img    = $Img->get();

        return view('reportCustImg',compact('Img'));
    }

    private function GetEmpName(){
        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.Active','Y')
                        ->orderBy('lmEmpDriv.EmpDriverCode')
                        ->get();
        return $EmpName;
    }

    private function GetCarGroup(){
        $Car = DB::table('LMDBM.dbo.lmEmpDriv')
                ->join('dbwins_new1.dbo.EMEmp as ememp','lmEmpDriv.EmpDriverCode','ememp.EmpCode')
                ->join('dbwins_new1.dbo.EMEmpGroup AS EMEmpGroup','ememp.EmpGroupID','EMEmpGroup.EmpGroupID')
                ->where('EMEmpGroup.EmpGroupCode','<>','001')
                ->select('EMEmpGroup.EmpGroupCode', 'EMEmpGroup.EmpGroupName')
                ->distinct()
                ->get();

        return $Car;
    }
}
