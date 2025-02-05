<?php 
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;

    function ShowDate($date,$format = "d/m/Y H:i"){
        $date1      = date_create($date);
        $date2      = date_format($date1,$format);
        return $date2;
    }

    function MenuAll(){
        $menu = DB::table('LMSmenu')
                ->where('menuChildren',0)
                ->orderBy('listNo','ASC')
                ->get();
        return $menu;
    }

    function CheckSubMenu($id){
        $menu = DB::table('LMSmenu')
                ->join('LMSmenu_Permission','LMSmenu_Permission.Menu_id','LMSmenu.id')
                ->join('LMSusers','LMSmenu_Permission.EmpCode','LMSusers.EmpCode')
                ->where('menuChildren',$id)
                ->where('LMSmenu_Permission.EmpCode',Auth::user()->EmpCode)
                ->orderBy('listNo','ASC')
                ->get();
        return $menu;
    }

    function SubMenu($id){
        $menu = DB::table('LMSmenu')
                ->where('menuChildren',$id)
                ->orderBy('listNo','ASC')
                ->get();
        return $menu;
    }

    function GetPerUserName($MenuID){
        $PerUser = DB::table('LMSmenu_Permission')
                    ->select('LMSusers.Fullname')
                    ->join('LMSusers','LMSmenu_Permission.EmpCode','LMSusers.EmpCode')
                    ->where('LMSmenu_Permission.Menu_id',$MenuID)
                    ->get();

        $Fullname = '';
        foreach ($PerUser as $key => $value) {
            $Fullname .=  $value->Fullname.",";
        }
        return $Fullname;

    }

    function getMonth($m){
        $strMonthCut = Array(
                    "00" => "",
                    "01" =>"ม.ค.",
                    "02" =>"ก.พ.",
                    "03" =>"มี.ค.",
                    "04" =>"เม.ย.",
                    "05" =>"พ.ค.",
                    "06" =>"มิ.ย.",
                    "07" =>"ก.ค.",
                    "08" =>"ส.ค.",
                    "09" =>"ก.ย.",
                    "10" =>"ต.ค.",
                    "11" =>"พ.ย.",
                    "12" =>"ธ.ค.");
        return $strMonthCut[$m];
    }

    function GetScoreSumRate($empCode,$Month,$Year){
        $Score = DB::table('LMSRateEmpScore')
                ->selectRaw("SUM(scoreRate) AS sumScore , COUNT(scoreRate) as countScore")
                ->where('empDrivCode',$empCode)
                ->whereRaw("( scoreUseMonth = '$Month' AND scoreUseYear = '$Year') ")
                ->first();
        return $Score;
    }

    function GetRateTitle_Sub($year,$title_id,$sub_id = null){
        $GetDataTitle = DB::table('LMSRateEmpDriv_Title')
                        ->where([
                            'id' => $title_id,
                            'UseYear' => $year
                        ]);
        $GetDataTitle = $GetDataTitle->get();

        
        $SubTitle = DB::table('LMSRateEmpDriv_Title')
                        ->where([
                            'Parent' => $title_id,
                            'UseYear' => $year
                        ]);
        if($sub_id != ""){
            $SubTitle = $SubTitle->whereIn('id',$sub_id);
        }
        $SubTitle = $SubTitle->get();

        
        $data['Title']      = $GetDataTitle;
        $data['SubTitle']   = $SubTitle;

        return $data;
    }

    function GetScore_Count_RateEmp($empCode,$subId,$Month,$Year){
        $Score = DB::table('LMSRateEmpScore')
                    ->where('subTitleId',$subId)
                    ->where('empDrivCode',$empCode)
                    ->where('scoreUseMonth', $Month)
                    ->where('scoreUseYear', $Year)
                    ->sum('scoreRate');

        $CountScore = DB::table('LMSRateEmpScore')
                    ->where('subTitleId',$subId)
                    ->where('empDrivCode',$empCode)
                    ->where('scoreUseMonth', $Month)
                    ->where('scoreUseYear', $Year)
                    ->count();


        $data['TotalScore']         = $Score;
        $data['CountTotalScore']    = $CountScore;

        return $data;
    }

    function GetScoreRateEmpDriv($empCode,$subId,$Month,$Year){
        $Score = DB::table('LMSRateEmpScore')
                    ->select('scoreRate','remark')
                    ->where('subTitleId',$subId)
                    ->where('empDrivCode',$empCode)
                    ->where('scoreUseMonth', $Month)
                    ->where('scoreUseYear', $Year)
                    ->first();

        $drivScore = [];

        if($Score != ""){
            $drivScore['score']  = $Score->scoreRate;
       
            $drivScore['remark'] = $Score->remark;
                       
        }
        
        return $drivScore;
    }

    function GetLeaveWork($empCode,$Month,$Year,$leave_id){
        $leave = DB::table('LMSLogEmpDriv_Leave')
                ->where([
                    'empDrivCode'=>$empCode,
                ])
                ->whereMonth('leave_date_start', '<=', $Month)
                ->whereYear('leave_date_start', '=', $Year)
                ->where('leave_id',$leave_id)
                ->get();
        $H = 0;
        if(count($leave) != 0){
            foreach ($leave as $key => $value) {
                if($value->leave_type == 'D'){
                    $H += $value->leave_amount*8;
                }elseif($value->leave_type == 'H'){
                    $H += $value->leave_amount;
                }  
            }
        } 
        return $H;
    }

    function GetWorkEmp_Day($empCode,$day){
        $Leave_day = DB::table('LMSLogEmpDriv_Leave_dt as LMSLogEmpDriv_Leave_dt')
                    ->join('LMSLogEmpDriv_Leave as LMSLogEmpDriv_Leave','LMSLogEmpDriv_Leave_dt.leave_id','LMSLogEmpDriv_Leave.id')
                    ->join('LMSLeaveWork as LMSLeaveWork','LMSLogEmpDriv_Leave.leave_id','LMSLeaveWork.id')
                    ->select('LMSLogEmpDriv_Leave.leave_type','LMSLogEmpDriv_Leave.leave_amount','LMSLogEmpDriv_Leave.empDrivCode','LMSLeaveWork.leave_name','LMSLeaveWork.leave_limit_date')
                    ->whereRaw("CONVERT(varchar,LMSLogEmpDriv_Leave_dt.day_off, 112) = '$day' AND LMSLogEmpDriv_Leave_dt.empDrivCode = $empCode ")
                    ->first();
        return $Leave_day;
    }

    function ConvertLeaveStr($H){

        $days = floor($H / 8);
    
        // หาจำนวนชั่วโมงที่เหลือหลังจากหักชั่วโมงของวัน
        $remaining_hours = $H % 8;
    
        // แสดงผลลัพธ์
        if ($days > 0) {
            if ($remaining_hours > 0) {
                $str_h =  "$days วัน $remaining_hours ชั่วโมง";
            } else {
                $str_h =  "$days วัน";
            }
        } else {
            $str_h =  "$remaining_hours ชั่วโมง";
        }

        return $str_h;
    }

    

    function MonthThai($month){
        if($month == "00"){
            $month = "12";
        }
        $strMonthCut = Array("01"=>"มกราคม"
                                ,"02"=>"กุมภาพันธ์"
                                ,"03"=>"มีนาคม"
                                ,"04"=>"เมษายน"
                                ,"05"=>"พฤษภาคม"
                                ,"06"=>"มิถุนายน"
                                ,"07"=>"กรกฎาคม"
                                ,"08"=>"สิงหาคม"
                                ,"09"=>"กันยายน"
                                ,"10"=>"ตุลาคม"
                                ,"11"=>"พฤจิกายน"
                                ,"12"=>"ธันวาคม");
        return $strMonthCut[$month];
    }

    function DateThai($strDate,$ShowTime = true){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
	
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
        if($ShowTime){
            return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
        }else{
            return "$strDay $strMonthThai $strYear";
        }
		
	}

    function GetNotification(){
        $Notify = DB::table('LMSNottification')->orderByDesc('Datetime')->limit(15)->get();
        return $Notify;
    }

    function CheckCancelContainer($Container){

        $tmConTain_bk = DB::table('TMDBM.dbo.tmConTain_bk')
                        ->select('Flag_st')
                        ->where(['ContainerNO'=>$Container,
                                'Flag_st'=>'R'])
                        ->first();

        $tmConTain_dl = DB::table('TMDBM.dbo.tmConTain_dl')
                        ->select('Flag_st')
                        ->where(['ContainerNO'=>$Container,
                                'Flag_st'=>'R'])
                        ->first();

       $tmConTain   = DB::table('TMDBM.dbo.tmConTain')
                        ->select('Flag_st')
                        ->where(['ContainerNO'=>$Container,
                                'Flag_st'=>'R'])
                        ->first();

        if(
            ($tmConTain_bk != '' && $tmConTain_bk->Flag_st == "R") || 
            ($tmConTain_dl != '' && $tmConTain_dl->Flag_st == "R") || 
            ($tmConTain != '' && $tmConTain->Flag_st == "R")
        ){
            $Contain_Flag = 'Y';
        }else{
            $Contain_Flag = 'N';
        }

        return $Contain_Flag;
    }

    function sumToPercent($cal,$sum){
        return round(($cal/$sum)*100,2);
    }

    function GetFinComp(){
        $FinComp = DB::table('LMDBM.dbo.lmFinComp')->get();
        return $FinComp;
    }

    function GetEmpDriv(){
        $EmpDriv = DB::table('LMDBM.dbo.lmEmpDriv')->where('Active','Y')->get();
        return $EmpDriv;
    }

    function GetInsurance(){
        $Insurance = DB::table('LMDBM.dbo.lmInsComp')->get();
        return $Insurance;
    }

    function GetInsuranceType(){
        $InsuranceType = DB::table('LMDBM.dbo.lmInsType')->get();
        return $InsuranceType;
    }

    function GetOilType(){
        $Olis = DB::table('LMDBM.dbo.lmOilType')->get();

        return $Olis;
    }

    function GetCarType(){
        $CarType = DB::table('LMDBM.dbo.lmCarType')->get();

        return $CarType;
    }

    function GetProvince(){
        $lmProThai_tm = DB::table('LMDBM.dbo.lmProThai_tm')->get();

        return $lmProThai_tm;
    }

    function GetCarBrand(){
        $CarBand = DB::table('LMDBM.dbo.lmCarBand')->get();

        return $CarBand;
    }

    function GetFormula(){
        $Formula = DB::table('LMDBM.dbo.lmSetFormula')->get();

        return $Formula;
    }

    function GetFormula_co(){
        $Formula = DB::table('LMDBM.dbo.lmSetFormula_co')->get();

        return $Formula;
    }

    function GetOilComp(){
        $lmOilComp = DB::table('LMDBM.dbo.lmOilComp')->get();

        return $lmOilComp;
    }

    function GetGroupEmp(){
        $lmGroupEmp = DB::table('LMDBM.dbo.lmEmpGrop')->get();

        return $lmGroupEmp;
    }
?>