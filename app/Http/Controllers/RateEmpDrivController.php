<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Storage;

class RateEmpDrivController extends Controller
{
    //
    public function setFrom(Request $req){
        $RateTitle = '';

        $SumScore  = '';

        $carSize   = '';

        $groupCode = '';

        $yearSelect = '';
        
        if(isset($req->carSize)){
            $carSize        = $req->carSize;
            $yearSelect     = $req->year;
            $groupCode      = $req->groupCode;

            $RateTitle  = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('CarType',$carSize)
                            ->where('UseYear',$yearSelect);
            if($groupCode != "A"){
                $RateTitle  = $RateTitle->where('CarGroupCode',$groupCode);
            }elseif($groupCode == "A") {
                $RateTitle  = $RateTitle->whereNull('CarGroupCode');
            }
            $RateTitle  = $RateTitle->get();

            $SumScore   = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('CarType',$carSize)
                            ->where('UseYear',$yearSelect);
                            if($groupCode != "A"){
                                $SumScore  = $SumScore->where('CarGroupCode',$groupCode);
                            }elseif($groupCode == "A") {
                                $SumScore  = $SumScore->whereNull('CarGroupCode');
                            }
                            $SumScore  = $SumScore->Sum('Score');
        }

        return view('rateEmpCar.setFromScore',compact('RateTitle','SumScore','carSize','yearSelect','groupCode'));
    }

    public function rateEmp(Request $req){
        $Month_rate = Carbon::now()->format('m');
        if($req->Month_rate != ""){
            $Month_rate     = $req->Month_rate;

            $firstM  =  Carbon::now()->format("Y".$Month_rate."01");
            $lastM   =  Carbon::now()->format("Y".$Month_rate."t");

        }else{
            $firstM  =  Carbon::now()->format('Ym01');
            $lastM   =  Carbon::now()->format('Ymt');
        }

        $Year   =  Carbon::now()->format('Y');
        // dd($Month_rate,$Year);

        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode','lmEmpDriv.EmpDriverTel')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->selectRaw("(SELECT        SUM(res.scoreRate) AS Expr1
                        FROM            LMSRateEmpScore AS res
                        WHERE    ( res.scoreUseMonth = '$Month_rate' AND res.scoreUseYear = '$Year' )   AND (res.empDrivCode = lmEmpDriv.EmpDriverCode)
                        GROUP BY res.empDrivCode) AS SumScoreRate")
                        ->selectRaw("(SELECT TOP(1) SubTitleName FROM LMSRateEmpScore as res2 WHERE (res2.empDrivCode = lmEmpDriv.EmpDriverCode) AND  ( res2.scoreUseMonth = '$Month_rate' AND res2.scoreUseYear = '$Year' ) ORDER BY res2.created_time DESC ) as SubTitleName")
                        ->selectRaw("(SELECT TOP(1) Fullname FROM LMSRateEmpScore as res3 INNER JOIN LMSusers as LMSusers ON res3.created_by = LMSusers.EmpCode WHERE (res3.empDrivCode = lmEmpDriv.EmpDriverCode) AND ( res3.scoreUseMonth = '$Month_rate' AND res3.scoreUseYear = '$Year' ) ORDER BY res3.created_time DESC ) as RateFullname")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.Active','Y')
                        ->orderByRaw("SumScoreRate DESC")
                        ->get();
        // dd($EmpName);
        return view('rateEmpCar.empRate',compact('EmpName','Month_rate'));
    }

    public function proFileEmpDriv(Request $req){
        $empCode        = $req->empCode;
        $Month_rate     = $req->Month_rate;
        $Year           =  Carbon::now()->format('Y');

        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode','lmEmpDriv.EmpDriverTel','lmEmpDriv.EmpGroupCode')
                        ->selectRaw("lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->selectRaw("(SELECT SUM(res.scoreRate) AS Expr1
                        FROM            LMSRateEmpScore AS res
                        WHERE       ( res.scoreUseMonth = '$Month_rate' AND res.scoreUseYear = '$Year' ) AND (res.empDrivCode = lmEmpDriv.EmpDriverCode)
                        GROUP BY res.empDrivCode) AS SumScoreRate")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.Active','Y')
                        ->where('lmEmpDriv.EmpDriverCode',$empCode)
                        ->first();

        $groupCode = $EmpName->EmpGroupCode;

        $Year = date('Y');
        $RateTitle  = DB::table('LMSRateEmpDriv_Title')
                        ->where('parent',0)
                        ->where('CarType',$EmpName->CarTypeCode)
                        ->where('UseYear',$Year);
        if($groupCode == "EG-0003"){
            $RateTitle  =    $RateTitle->where('CarGroupCode',$groupCode);
        }elseif($groupCode != "EG-0003"){
            $RateTitle  =    $RateTitle->whereNull('CarGroupCode');
        }
        $RateTitle  =    $RateTitle->get();
                        
        return view('rateEmpCar.profileEmpRate',compact('EmpName','RateTitle'));
    }

    public function proFileGetSubTitleRate(Request $req){
        $id = $req->val;

        $RateTitle  = DB::table('LMSRateEmpDriv_Title')->where('parent',$id)->get();
        
        return response()->json($RateTitle, 200);
    }

    public function saveRateEmp(Request $req){

        $idRateTitle    = $req->RateTitle;
        $idRateSubTitle = $req->RateSubTitle;
        $RateRemark     = $req->RateRemark;
        $EmpCode        = $req->EmpCode;
        $photo          = $req->file('imgRate');
        $Month_rate     = $req->Month_rate;
        $RateAmount     = $req->RateAmount;
        
        try {
            DB::beginTransaction();

            $RateTitle      = DB::table('LMSRateEmpDriv_Title')
                            ->where('id',$idRateTitle)
                            ->first();

            $SubRateTitle   = DB::table('LMSRateEmpDriv_Title')
                            ->where('id',$idRateSubTitle)
                            ->first();

            $filename = '';

            if($photo != ""){
                $filename       = date("d-m-Y_h-i-s")."_$EmpCode.".$photo->getClientOriginalExtension();

                $fileContents   = file_get_contents($photo->getPathname());

                Storage::disk('ftp_local')->put('empDrivRate/'.$filename, $fileContents);
            }
                    
            for ($i=0; $i < $RateAmount ; $i++) { 
                $EmpScore['scoreRate']      = $SubRateTitle->Score;
                $EmpScore['mainTitleId']    = $idRateTitle;
                $EmpScore['mainTitleName']  = $RateTitle->Title;
                $EmpScore['subTitleId']     = $idRateSubTitle;
                $EmpScore['subTitleName']   = $SubRateTitle->Title;
                $EmpScore['remark']         = $RateRemark;
                if($filename != ""){
                    $EmpScore['imgUrl']     = "https://images.jtpackconnect.com/ImageAllProducts/empDrivRate/".$filename;
                }
                $EmpScore['empDrivCode']    = $EmpCode;
                $EmpScore['created_by']     = Auth::user()->EmpCode;
                $EmpScore['scoreUseMonth']  = $Month_rate;
                $EmpScore['created_time']   = now();
    
                DB::table('LMSRateEmpScore')->insert($EmpScore);
            }
           
            DB::commit();

            return "success";

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function saveTitle(Request $req){
        // dd($req);
        $title      = $req->title;
        $score      = $req->score;
        $type       = $req->type;
        $Port       = Auth::user()->EmpCode;
        $CarType    = $req->CarType;
        $Year       = $req->Year;
        $groupCode  = $req->groupCode;

        try {
            DB::beginTransaction();

            if($type == 0){    
                $SumScore = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('CarType',$CarType)
                            ->where('UseYear',$Year)
                            ->where('CarGroupCode',$groupCode)
                            ->Sum('Score');
                // dd($SumScore,$score);
                if($SumScore+$score > 100){
                    return "scoreError";
                }

                $RateEmpTitle['Title']          = $title;
                $RateEmpTitle['Score']          = $score;
                $RateEmpTitle['Parent']         = 0;
                $RateEmpTitle['Created_by']     = $Port;
                $RateEmpTitle['Created_time']   = now();
                $RateEmpTitle['CarType']        = $CarType;
                $RateEmpTitle['UseYear']        = $Year;
                if($groupCode != "A"){
                    $RateEmpTitle['CarGroupCode'] =  $groupCode;
                }

                DB::table('LMSRateEmpDriv_Title')->insert($RateEmpTitle);
                DB::commit();
                
                return 'success';

            }elseif($type == 1){
                $id = $req->id;

                $SumScore = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('id','<>',$id)
                            ->where('CarType',$CarType)
                            ->where('CarGroupCode',$groupCode)
                            ->Sum('Score');

                if($SumScore+$score > 100){
                    return "scoreError";
                }

                $RateEmpTitle['Title']          = $title;
                $RateEmpTitle['Score']          = $score;
                $RateEmpTitle['Updated_by']     = $Port;
                $RateEmpTitle['Updated_time']   = now();
                // $RateEmpTitle['CarType']        = $CarType;

                DB::table('LMSRateEmpDriv_Title')->where('id',$id)->update($RateEmpTitle);
                DB::commit();
                
                return 'success';
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function getTitle(Request $req){
        $id = $req->id;

        $RateTitle = DB::table('LMSRateEmpDriv_Title')->where('id',$id)->first();

        return response()->json($RateTitle,200);
    }

    public function getSubTitle(Request $req){
        $id = $req->id;

        $RateTitle  = DB::table('LMSRateEmpDriv_Title')->where('parent',$id)->get();

        $SumScore   = DB::table('LMSRateEmpDriv_Title')->where('parent',$id)->Sum('Score');

        $Title      = DB::table('LMSRateEmpDriv_Title')->where('id',$id)->first();

        $ScoreTitle = $Title->Score;
        $Title      = $Title->Title;

        return view('rateEmpCar.setFromScore_dt',compact('RateTitle','SumScore','Title','ScoreTitle'));
    }

    public function deleteTitle(Request $req){
        $id = $req->id;
        try {
            $data = DB::table('LMSRateEmpDriv_Title')->where('id',$id)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    // Sub Title 
    public function saveSubTitle(Request $req){

        $title      = $req->titleSub;
        $score      = $req->scoreSub;
        $type       = $req->type;
        $Port       = Auth::user()->EmpCode;
        $CarType    = $req->CarType;
        $MainID     = $req->MainID;
        $Year       = $req->Year;
        $groupCode  = $req->groupCode;
        
        try {
            DB::beginTransaction();

            $SumScoreMain   = DB::table('LMSRateEmpDriv_Title')
                                ->where('id',$MainID)
                                ->where('UseYear',$Year);
            if($groupCode != "A"){
                $SumScoreMain   = $SumScoreMain->where('CarGroupCode',$groupCode);
            }
            
            $SumScoreMain   = $SumScoreMain->Sum('Score');    
     
            if($type == 0){    
            
                $SumScore       = DB::table('LMSRateEmpDriv_Title')
                                    ->where('Parent',$MainID)
                                    ->where('UseYear',$Year)
                                    ->where('CarGroupCode',$groupCode)
                                    ->Sum('Score');
                
                if($SumScore+$score > $SumScoreMain){
                    return "scoreError";
                }
    
                $RateEmpTitle['Title']          = $title;
                $RateEmpTitle['Score']          = $score;
                $RateEmpTitle['Parent']         = $MainID;
                $RateEmpTitle['Created_by']     = $Port;
                $RateEmpTitle['Created_time']   = now();
                $RateEmpTitle['CarType']        = $CarType;
                $RateEmpTitle['UseYear']        = $Year;
                if($groupCode != "A"){
                    $RateEmpTitle['CarGroupCode'] =  $groupCode;
                }
                DB::table('LMSRateEmpDriv_Title')->insert($RateEmpTitle);
                DB::commit();
                
                return 'success';

            }elseif($type == 1){
                $idSubEdit     = $req->idSubEdit;
                
                $SumScore       = DB::table('LMSRateEmpDriv_Title')
                                    ->where('Parent',$MainID)
                                    ->where('id','<>',$idSubEdit)
                                    ->where('UseYear',$Year)
                                    ->where('CarGroupCode',$groupCode)
                                    ->Sum('Score');
          
                if($SumScore+$score > $SumScoreMain){
                    return "scoreError";
                }
    
                $RateEmpTitle['Title']          = $title;
                $RateEmpTitle['Score']          = $score;
                $RateEmpTitle['Updated_by']     = $Port;
                $RateEmpTitle['Updated_time']   = now();
                $RateEmpTitle['CarType']        = $CarType;

                DB::table('LMSRateEmpDriv_Title')->where('id',$idSubEdit)->update($RateEmpTitle);
                DB::commit();
                
                return 'success';
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
