<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class addEmpController extends Controller
{
    //
    public function index(){

        $Cardriv    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                    ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                    ->where('lmCarDriv.IsDefault','Y')
                    ->join('dbwins_new1.dbo.EMTransp AS EMTransp','lmEmpDriv.TranspID','EMTransp.TranspID')
                    ->distinct()
                    ->select('EMTransp.TranspID')
                    ->selectRaw("REPLACE(EMTransp.TranspName, 'ขนส่งโดย', '') AS TranspName")
                    ->get();

        $leave = DB::table('LMSLeaveWork')->get();

        return view('addEmp',compact('Cardriv','leave'));
    }

    public function filter_emp(Request $req){

        $WorkDate   = $req->workdate;
        $CarSize    = $req->car_size;

        $WorkDate   = date_create($WorkDate);
        $WorkDate   = date_format($WorkDate,"Y-m-d H:i:s");

        $EmpDriv    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.TranspID',$CarSize)
                        ->orderBy('lmEmpDriv.EmpDriverCode')
                        ->get();


        $data['Emp']   = json_decode($EmpDriv,true);

        $Send_date = DB::table('LMSDBM.dbo.LMSwork_date As lmWorkDate')
                        ->join('LMDBM.dbo.lmEmpDriv AS lmEmpDriv','lmWorkDate.EmpDriverCode','lmEmpDriv.EmpDriverCode')  
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmWorkDate.Status','lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmWorkDate.SentDate',$WorkDate)
                        ->where('lmWorkDate.TranspID',$CarSize)
                        ->get();

        $Send_arr  = array();
        if(count($Send_date) != ""){
            $data['Send'] = json_decode($Send_date,true);
        }
        
        $data = json_encode($data,true);

        return $data;
    }

    public function save(Request $req){
        DB::beginTransaction();
       
        $Empcode    = $req->Empcode;

        $DateWork   = $req->DateWork; 
        $DateWork   = date_create($DateWork);
        $DateWork   = date_format($DateWork,"Y-m-d H:i:s");

        $UserId     = Auth::user()->EmpCode;

        foreach ($Empcode as $key => $value) {

            $EmpDriv  = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.EmpDriverCode',$value)
                        ->first();

            $Data_work['SentDate']          = $DateWork;
            $Data_work['EmpDriverCode']     = $EmpDriv->EmpDriverCode;
            $Data_work['CarLicense']        = $EmpDriv->VehicleCode;
            $Data_work['CarTypeCode']       = $EmpDriv->CarTypeCode;
            $Data_work['TranspID']          = $EmpDriv->TranspID;
            $Data_work['Status']            = 'Y';
            $Data_work['creater']           = $UserId;
            $Data_work['created_at']        = now();
            $Data_work['updater']           = $UserId;
            $Data_work['updated_at']        = now();

            try {
                $count =  DB::table('LMSDBM.dbo.LMSwork_date')->insert($Data_work);
                if($count > 1){
                    DB::rollBack();
                    return "fail1";
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                return $th;
            }     
        }
        DB::commit();
        return $Empcode;
    }

    public function change_status(Request $req){
        // dd($req);
        DB::beginTransaction();
        try{
            $DateWork   = $req->DateWork;
            $EmpCode    = $req->Empcode;
            $Status     = $req->Status;

            $SqlDate   = date_create($DateWork);
            $SqlDate   = date_format($SqlDate,"Ymd");


            if($Status == "N"){

                $type_id    = $req->type;
                $amount     = $req->amount;
                $day        = $req->day;
                $remark     = $req->remark;
        
                if($day == "D"){

                    $dateStart       =   Carbon::parse($DateWork);

                    $daysOff = [];
                    // $daysLeave = [];
                    for ($i = 0; $i < $amount; $i++) {

                        $sqlHoliday =  $dateStart->addDays($i);
                        $sqlHoliday =  $sqlHoliday->format('Y-m-d');
                        
                        $CheckHoliday = DB::table('TMDBM.dbo.tmTrnHoliday')
                                        ->whereRaw("HolidayDate = '".$sqlHoliday."'")
                                        ->count();

                        if (!$dateStart->isSunday() && $CheckHoliday == 0) {
                            $daysOff[]   = $dateStart->copy();
                            // $daysLeave[] = $dateStart->format('Y-m-d');
                        } else {
                            $i--;
                        }
                    }

                   
                    $indexDay       =   $amount-1;
                    $numOfDaysOff   =   count($daysOff)+1;
    
                    $dateEnd        =   $daysOff[$indexDay]->format('Y-m-d');
                    $dayStart       =   Carbon::parse($DateWork);
                  
                    $Log['leave_id']            = $type_id;
                    $Log['leave_amount']        = $amount;
                    $Log['empDrivCode']         = $EmpCode;
                    $Log['leave_date_start']    = $dayStart->format('Y-m-d');
                    $Log['leave_date_end']      = $dateEnd;
                    $Log['leave_type']          = $day;
                    $Log['Leave_remark']        = $remark;
                    $Log['created_by']          = Auth::user()->EmpCode;
                    $Log['created_time']        = now();

                    $lastId =  DB::table('LMSLogEmpDriv_Leave')->insertGetId($Log);


                    foreach ($daysOff as $key => $value) {
                        $SqlDate = $value->format('Ymd');

                        $UpdateWork['Status'] = $Status;

                        DB::table('LMSwork_date')
                            ->where('EmpDriverCode',$EmpCode)
                            ->whereRaw("CONVERT(varchar,SentDate,112) = '".$SqlDate."'")
                            ->update($UpdateWork);

                        $Log_dt['day_off']      = $value->format('Y-m-d');
                        $Log_dt['empDrivCode']  = $EmpCode;
                        $Log_dt['leave_id']     = $lastId;

                        DB::table('LMSLogEmpDriv_Leave_dt')->insert($Log_dt);
                    }

                }elseif($day == "H"){

                    $WorkDate   = date_create($DateWork);
                    $WorkDate   = date_format($WorkDate,"Y-m-d");

                    $Log['leave_id']            = $type_id;
                    $Log['leave_amount']        = $amount;
                    $Log['empDrivCode']         = $EmpCode;
                    $Log['leave_date_start']    = $WorkDate;
                    $Log['leave_date_end']      = $WorkDate;
                    $Log['leave_type']          = $day;
                    $Log['Leave_remark']        = $remark;
                    $Log['created_by']          = Auth::user()->EmpCode;
                    $Log['created_time']        = now();

                    DB::table('LMSLogEmpDriv_Leave')->insert($Log);

                    $UpdateWork['Status'] = $Status;


                    $count = DB::table('LMSwork_date')
                                ->where('EmpDriverCode',$EmpCode)
                                ->whereRaw("CONVERT(varchar,SentDate,112) = '".$SqlDate."'")
                                ->update($UpdateWork);

                    if($count > 1){
                        DB::rollBack();
                        return "fail";
                    }
                }

            }else{
                $UpdateWork['Status'] = $Status;
                $count = DB::table('LMSwork_date')
                            ->where('EmpDriverCode',$EmpCode)
                            ->whereRaw("CONVERT(varchar,SentDate,112) = '".$SqlDate."'")
                            ->update($UpdateWork);
                // dd($count,$EmpCode,$WorkDate);
                if($count > 1){
                    DB::rollBack();
                    return "fail";
                }
            }

        }catch (\Throwable $th) {
            DB::rollBack();
            return $th->getMessage();
        }    

        DB::commit();
        return "success";
    }

}
