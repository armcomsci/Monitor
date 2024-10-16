<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class dashboardController extends Controller
{
    public function index(){
        $dateNow =  date("Y-m-d");
        $firstM  =  Carbon::now()->format('Ym01');
        $lastM   =  Carbon::now()->format('Ymt');
       
        $data['Workdate']              = DB::table('DTDBM.dbo.vEMTransp as transp')
                                            ->join('LMDBM.dbo.lmCarType as cType','transp.CarType','cType.CarTypeCode')
                                            ->join('LMSTranSend_Amount as Tran_amount', function($join) use ($dateNow){
                                                $join->on('transp.TranspID','Tran_amount.TranspId')
                                                ->where("SendDate",$dateNow);
                                            })
                                            ->select("transp.CarType","cType.CarTypeName")
                                            ->selectRaw("SUM(Tran_amount.Amount) as SumDrive")
                                            ->groupBy("transp.CarType","cType.CarTypeName")
                                            ->get();

        $dateNow2 =  date("Ymd");

        $data['SumCarDriv_CT001']             =   $this->SumCarDriv('CT001',$dateNow2);

        $data['SumCarDriv_CT002']             =   $this->SumCarDriv('CT002',$dateNow2);  

        
        $data['SumCarDriv_CT003']             =   $this->SumCarDriv('CT003',$dateNow2);

        $arraySelect                    =   array('empDrive.EmpDriverName','empDrive.EmpDriverLastName','Container.ContainerNO','Container.created_at','Container.updated_at');

        $data['LastCheckOut']            =   DB::table('LMDBM.dbo.lmEmpContainers as Container')
                                                ->join('LMDBM.dbo.lmEmpDriv as empDrive','Container.EmpCode','empDrive.EmpDriverCode')
                                                ->select($arraySelect)
                                                ->where('Container.flag','N')
                                                ->whereRaw("(CONVERT(varchar, Container.created_at, 112) = '$dateNow2') ")
                                                ->orderByDesc('Container.updated_at')
                                                ->limit(10)
                                                ->get();

        $data['LastCheckIN']           =   DB::table('LMDBM.dbo.lmEmpContainers as Container')
                                                ->join('LMDBM.dbo.lmEmpDriv as empDrive','Container.EmpCode','empDrive.EmpDriverCode')
                                                ->select($arraySelect)
                                                ->where('Container.flag','Y')
                                                ->whereRaw("(CONVERT(varchar, Container.created_at, 112) = '$dateNow2') ")
                                                ->orderByDesc('Container.created_at')
                                                ->limit(10)
                                                ->get();

        $selectEmpNow   = DB::table('LMDBM.dbo.lmEmpTran_Now as sentNow')
                             ->select('EmpDriverCode','EmpDriverFullName','Stamp_date','VehicleCode','CarTypeCode')
                            ->selectRaw("(select top(1) EmpStamp_Times from  LMDBM.dbo.lmEmpTran_Now where  EmpDriverCode = sentNow.EmpDriverCode and  Stamp_Date = sentNow.Stamp_Date  ORDER BY EmpStamp_Times DESC) as EmpRun")
                            ->where('sentNow.Stamp_date','>=',$firstM)
                            ->where('sentNow.Stamp_date','<=',$lastM)
                            ->where('sentNow.EmpDriverCode','not like','200%')
                            ->distinct();
                            // ->groupBy('EmpDriverCode','EmpDriverFullName','VehicleCode','CarTypeCode');

        $EmpTran =  DB::table('LMDBM.dbo.lmEmpTran_Sent as tran_sent')
                        ->union($selectEmpNow)
                        ->select('EmpDriverCode','EmpDriverFullName','Stamp_date','VehicleCode','CarTypeCode')
                        ->selectRaw("(select top(1) EmpStamp_Times from  LMDBM.dbo.lmEmpTran_Sent where  EmpDriverCode = tran_sent.EmpDriverCode  and Stamp_Date = tran_sent.Stamp_Date ORDER BY LMDBM.dbo.lmEmpTran_Sent.EmpStamp_Times DESC) as EmpRun")
                        ->distinct()
                        ->where('tran_sent.Stamp_date','>=',$firstM)
                        ->where('tran_sent.Stamp_date','<=',$lastM)
                        ->where('tran_sent.EmpDriverCode','not like','200%')
                        // ->groupBy('EmpDriverCode','EmpDriverFullName','VehicleCode','CarTypeCode')
                        // ->orderby('SumRun','DESC')
                        ->get();
   
        $SumEmp     = [];
        foreach ($EmpTran as $key => $emp) {
            $Empcode                       = $emp->EmpDriverCode;
         
            if(isset($SumEmp[$Empcode]['StampSum'])){
                $Count                          = $SumEmp[$Empcode]['StampSum'];
                $SumEmp[$Empcode]['StampSum']   = $Count+$emp->EmpRun;
            }else{
                $SumEmp[$Empcode]['StampSum']   = $emp->EmpRun;
            }
            $SumEmp[$Empcode]['EmpName']   = $emp->EmpDriverCode." : ".$emp->EmpDriverFullName;

            // $i++;
        }
        usort($SumEmp, function ($a, $b) {
            return $b["StampSum"] - $a["StampSum"];
        });

        $data['SumRun'] = $SumEmp;

        $data['Comment']                = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain_rm as contain_rm')
                                            ->join('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as contain','contain_rm.ContainerNo','contain.ContainerNo')
                                            ->select('contain_rm.Remark','contain_rm.RemarkTime','contain.EmpName','contain_rm.ContainerNo')
                                            ->whereNotNull('contain_rm.Remark')
                                            ->orderBy('contain_rm.RemarkTime','DESC')
                                            ->limit(10)
                                            ->get();

        $data['SumScore']              =  DB::table('LMSScoreJob as score')
                                            ->join('LMSusers as LmsUser','score.Empcode','LmsUser.Empcode')
                                            ->select('LmsUser.Fullname','LmsUser.EmpCode')
                                            ->selectRaw('SUM(score.Score) as TotalScore')
                                            ->whereRaw("(CONVERT(varchar, score.DateTime, 112) BETWEEN '$firstM' AND '$lastM'  ) ")
                                            // ->where('LmsUser.')
                                            ->groupBy('LmsUser.EmpCode','LmsUser.Fullname')
                                            ->orderBydesc('TotalScore')
                                            ->get();
   
        return view('index',compact('data'));
    }

    private function SumCarDriv($type,$date){
        $data = DB::table('DTDBM.dbo.vEMTransp as transp')
                            ->join('LMDBM.dbo.lmEmpDriv as empDrive','empDrive.TranspID','transp.TranspID')
                            ->join('LMDBM.dbo.lmEmpContainers as Container','Container.EmpCode','empDrive.EmpDriverCode')
                            ->selectRaw("COUNT(DISTINCT Container.ContainerNO) As SumEmp, transp.CarType")
                            ->where('transp.CarType',$type)
                            ->whereRaw("(CONVERT(varchar, Container.created_at, 112) = '$date') ")
                            ->groupBy('transp.CarType')
                            ->first();
        return $data;
    }

    public function gpsCarAll(){
        $data = DB::table('LMSLogGps_temp')
                // ->leftjoin('LMDBM.dbo.lmCarDriv as lmCarDriv','lmCarDriv.VehicleCode','LMSLogGps_temp.vehicle_id')
                // ->leftjoin('LMDBM.dbo.lmEmpDriv as lmEmpDriv','lmCarDriv.EmpDriverCode','lmEmpDriv.EmpDriverCode')
                // ->select('LMSLogGps_temp.*','lmEmpDriv.EmpDriverName','lmEmpDriv.EmpDriverLastName')
                ->get();
        return response()->json($data, 200);
    }

    public function createView(){
        DB::beginTransaction();

        $str = '{
            "Sheet1": [
                {
                    "Title": "EMCust"
                },
                {
                    "Title": "EMCustContact"
                },
                {
                    "Title": "EMCustMultiEmp"
                },
                {
                    "Title": "EMCustType"
                },
                {
                    "Title": "EMEmp"
                },
                {
                    "Title": "EMShipto"
                },
                {
                    "Title": "EMTransp"
                },
                {
                    "Title": "plGodPrice"
                },
                {
                    "Title": "plGodPrice_hmt"
                }
            ]
        }';


        $data =  json_decode($str,true);
        try {
            $sql = '';
            foreach ($data['Sheet1'] as $key => $value) {

                $Title = $value['Title'];
                // $getRow = DB::table("dbwins_new1.dbo.$Title")->get();
                // dd($getRow);
                // dd("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'dbwins_new1.dbo.$Title'");
                // dd("CREATE VIEW [$Title] AS SELECT * FROM dbwins_new1.dbo.$Title GO");
                $sql .= "CREATE VIEW [$Title] AS SELECT * FROM dbwins_new1.dbo.$Title <br> GO <br>";
                $sql .= "<br>";
                // DB::select("CREATE VIEW [$Title] AS exec SELECT * FROM dbwins_new1.dbo.$Title GO");
            }
            echo $sql;
            DB::commit();
            // return "created success";
        } catch (\Throwable $th) {
            DB::rollback();

            return $th->getMessage();
        }


    }

    public function getNotify(){
        $notify = GetNotification();
        return response()->json($notify, 200);
    }

    public function getRemarkDriver(){
        $firstM  =  Carbon::now()->format('Ym01');
        $lastM   =  Carbon::now()->format('Ymt');
        $Port    = Auth::user()->EmpCode;

        $Remark = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain_rm as contain_rm')
                    ->join('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain','contain_rm.ContainerNo','m_contain.ContainerNo')
                    ->join('LMSJob_Contain as job','contain_rm.ContainerNo','job.ContainerNo')
                    ->select('contain_rm.Remark','contain_rm.ContainerNo','m_contain.EmpName','contain_rm.RemarkTime','contain_rm.CustID','contain_rm.ShipListNo')
                    // ->distinct()
                    ->whereRaw("(CONVERT(varchar, contain_rm.RemarkTime, 112) BETWEEN '$firstM' AND '$lastM'  ) ")
                    ->where('job.EmpCode',$Port)
                    ->whereNotNull('contain_rm.Remark')
                    // ->groupBy('contain_rm.TextAlert','contain_rm.ContainerNo','m_contain.EmpName','contain_rm.Datetime','contain_rm.CustID','contain_rm.ShipListNo')
                    ->orderBy('contain_rm.RemarkTime','DESC')
                    ->limit(10)
                    ->get();

        return response()->json($Remark, 200);
    }
}
