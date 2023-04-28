<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public $Curent_date,$Ago_date;

    public function __construct()
    {
        $this->Curent_date = date('Ymd');

        if(date('w')  == "1"){
            $this->Ago_date    = date('Ymd',strtotime(' -2 day'));
        }else{
            $this->Ago_date    = date('Ymd',strtotime(' -1 day'));
        }
        
    }

    public function index(){
        // dd( DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')->limit(10)->get());
        return view('login');
    }
    
    public function checkLogin(Request $req){
        DB::beginTransaction();
       
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
       
        // dd(Hash::make($req->password));
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $res['status'] = "01";
            $res['text']   = $error;
            return $res;
        }

        $emp = str_replace('-','',$req->username);
        $checkuser = DB::table('LMSusers')->where('EmpCode','LIKE',$emp)->first();

        if($checkuser != ""){
            if(Hash::check($req->password,$checkuser->password)){

                Auth::loginUsingId($checkuser->id,true);

                if (Auth::check()) {
                   
                    $EmpCode = Auth::user()->EmpCode;

                    $this->updateLog($EmpCode);

                    
                    
                    $Count_Contain =  $this->CheckPort('1');
        
                    $CheckLoginDay  = $this->CountUserOnline();
                    
                    $OldJob         = $this->OldJob($EmpCode);

                    if(Auth::user()->type == 1){
                        if($CheckLoginDay == 1){

                            $TodayJob  =  round( (($Count_Contain)/2) , 0 , PHP_ROUND_HALF_UP );

                            $UpdateJob =  $this->RandomPort($EmpCode,$TodayJob);

                        }else{

                            $Count_Contain_Port =  $this->CheckJobEmp($EmpCode);
                            // dd($Count_Contain_Port,$Count_Contain,$OldJob,$CheckLoginDay);
                            $avg_Job            = round((($Count_Contain_Port+$Count_Contain)+$OldJob)/$CheckLoginDay, 0 , PHP_ROUND_HALF_UP );
                        
                            if($avg_Job > $Count_Contain_Port){
                                $TodayJob = $Count_Contain_Port;
                            }else{
                                $TodayJob = $avg_Job;
                            }
                                
                            $UpdateJob          =  $this->RandomPort($EmpCode,$TodayJob);
                        }  
                        $status = "00";
                        $text   = "งานทั้งหมด : ".$UpdateJob;
                    }else{
                        $status = "00";
                        $text   = "ยินดีต้อนรับ : ".Auth::user()->Fullname;
                    }
                    DB::commit();

                }else{
                    $status = "02";
                    $text  = "เกิดปัญหาเกี่ยวกับระบบ";
                }
            }else{
                $status = "03";
                $text = "รหัสผ่านไม่ถูกต้อง กรุณาเช็ครหัสผ่าน";
            }
        }else{
            $status = "04";
            $text = "ผู้ใช้งานไม่ถูกต้อง กรุณาเช็คผู้ใช้งาน";
        }
        $res['status'] = $status;
        $res['text']   = $text;
        return $res;
        
    }

    public function logout(){
        $EmpCode = Auth::user()->EmpCode;

        $log_login['Status_online'] = 'N';
        $log_login['Logout_time']    = now();
        

        DB::table('LMSLog_login')
            ->where('EmpCode',$EmpCode)
            ->whereRaw("CONVERT(varchar,Login_time,112) = '$this->Curent_date' ")
            ->update($log_login);
        
        Auth::logout();
        return redirect('Login');
    }

    public function RandomPort($EmpCode,$TodayJob){
        try {
            // $CheckLogin = $this->CountUserOnline($EmpCode);

            $CheckJobUpdate =  DB::table('LMSJob_Contain')
                                ->where('EmpCode',$EmpCode)
                                ->whereRaw("CONVERT(varchar,Datetime,112) = '$this->Curent_date'")
                                ->select('ContainerNo','Port')
                                ->count();
                
            if($CheckJobUpdate == 0){

                $UpdatePort['Port'] = $EmpCode;
                $UpdatePort['Port_Updated'] = now();
                
                // $Count =   DB::table('LMDBM.dbo.lmEmpContainers')
                //         ->whereRaw("id IN ( SELECT TOP($TodayJob) id FROM LMDBM.dbo.lmEmpContainers WHERE CONVERT(varchar,updated_at,112) BETWEEN '$this->Ago_date' AND '$this->Curent_date' AND Port IS NULL AND flag = 'N' ORDER BY RAND() )")
                //         ->update($UpdatePort);
                $select = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                            ->leftjoin('LMSJob_Contain as contain','m_contain.ContainerNo','contain.ContainerNo')
                            ->whereNull('contain.EmpCode')
                            ->whereRaw("CONVERT(varchar,SaveDate,112) BETWEEN '$this->Ago_date' AND '$this->Curent_date'  ")
                            ->select('m_contain.ContainerNo')
                            ->selectRaw("'$EmpCode' as EmpCode")
                            ->limit($TodayJob)
                            ->orderByRaw('RAND()');
               
                DB::table('LMSJob_Contain')->insertUsing(['ContainerNo','EmpCode'],$select);


                $log    =  new scoreboardController();
                $detail = "รับงานใหม่ จำนวน ".$TodayJob." งาน";
                $code   = "00";
                $log->saveLogEvent($detail,$code);
            }

            $AllJob = DB::table('LMSJob_Contain')
                        ->where('EmpCode',$EmpCode)
                        ->where('Status','N')
                        // ->whereRaw("CONVERT(varchar,Datetime,112) BETWEEN '$this->Ago_date' AND '$this->Curent_date'")
                        ->select('ContainerNo','Port')
                        ->count();
            return $AllJob;
        
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function CountUserOnline($EmpCode = null){
        try {
            $userOnline  = DB::table('LMSLog_login');
                            $userOnline  = $userOnline->where('Status_online','Y');
                            $userOnline  = $userOnline->whereRaw("CONVERT(varchar,Login_time,112) = '$this->Curent_date' ");
                            if($EmpCode != ""){
                                $userOnline  = $userOnline->where('EmpCode',$EmpCode);
                            }
                            $userOnline  = $userOnline->count();
            return $userOnline;
        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }

    public function OldJob($EmpCode){
        try {
            $oldJob = DB::table('LMSJob_Contain')->where('EmpCode',$EmpCode)->count();
            return $oldJob;
        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }

    public function CheckPort($count){
        try {
            $CheckPort = DB::table('LKJTCLOUD_DTDBM.DTDBM.dbo.nlmMatchContain as m_contain')
                        ->leftjoin('LMSJob_Contain as contain','m_contain.ContainerNo','contain.ContainerNo');
            if($count == '1'){
                $CheckPort =   $CheckPort->whereNull('contain.EmpCode');
                $CheckPort =   $CheckPort->whereRaw("CONVERT(varchar,m_contain.SaveDate,112) BETWEEN '$this->Ago_date' AND '$this->Curent_date' ")
                                ->count();
            }elseif($count == '2'){
                $CheckPort =   $CheckPort->whereNull('contain.EmpCode');
                $CheckPort =   $CheckPort->whereRaw("CONVERT(varchar,m_contain.SaveDate,112) = '$this->Curent_date' ")
                                ->count();
            }
          
            return $CheckPort;
        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }

    public function CheckJobEmp($EmpCode){
        try {
            $checkRow = DB::table('LMSJob_Contain')
                        ->where('EmpCode','<>',$EmpCode)
                        ->count();
            return $checkRow;
        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }

    public function UpdateLog($EmpCode){
        try {
            
            $log_login['EmpCode']       = $EmpCode;
            $log_login['Status_online'] = 'Y';
            $log_login['Login_time']    = now();
            
            $checkRow = DB::table('LMSLog_login')
                        ->where('EmpCode',$EmpCode)
                        ->whereRaw("CONVERT(varchar,Login_time,112) = '$this->Curent_date' ")
                        ->count();

            if($checkRow == 0){
                DB::table('LMSLog_login')->insert($log_login);
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }
}
