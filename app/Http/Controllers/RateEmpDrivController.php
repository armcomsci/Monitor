<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RateEmpDrivController extends Controller
{
    //
    public function setFrom(Request $req){
        $RateTitle = '';

        $SumScore  = '';

        $carSize   = '';

        $yearSelect = '';
        
        if(isset($req->carSize)){
            $carSize        = $req->carSize;
            $yearSelect     = $req->year;

            $RateTitle  = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('CarType',$carSize)
                            ->where('UseYear',$yearSelect)
                            ->get();

            $SumScore   = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('CarType',$carSize)
                            ->where('UseYear',$yearSelect)
                            ->Sum('Score');
        }

       

        return view('rateEmpCar.setFromScore',compact('RateTitle','SumScore','carSize','yearSelect'));
    }

    public function rateEmp(){

        $firstM  =  Carbon::now()->format('Ym01');
        $lastM   =  Carbon::now()->format('Ymt');

        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode','lmEmpDriv.EmpDriverTel')
                        ->selectRaw("lmEmpDriv.EmpDriverCode + ' : ' + lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->selectRaw("(SELECT        SUM(res.scoreRate) AS Expr1
                        FROM            LMSRateEmpScore AS res
                        WHERE        (CONVERT(varchar, res.created_time, 112) BETWEEN '$firstM' AND '$lastM') AND (res.empDrivCode = lmEmpDriv.EmpDriverCode)
                        GROUP BY res.empDrivCode) AS SumScoreRate")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.Active','Y')
                        ->orderByRaw("CASE WHEN (SELECT SUM(res.scoreRate) FROM LMSRateEmpScore AS res WHERE (CONVERT(varchar, res.created_time, 112) BETWEEN '20240201' AND '20240229') AND (res.empDrivCode = lmEmpDriv.EmpDriverCode) GROUP BY res.empDrivCode) IS NULL THEN 1 ELSE 0 END, (SELECT SUM(res.scoreRate) FROM LMSRateEmpScore AS res WHERE (CONVERT(varchar, res.created_time, 112) BETWEEN '$firstM' AND '$lastM') AND (res.empDrivCode = lmEmpDriv.EmpDriverCode) GROUP BY res.empDrivCode) ASC")
                        ->get();

        return view('rateEmpCar.empRate',compact('EmpName'));
    }

    public function proFileEmpDriv(Request $req){
        $empCode = $req->empCode;

        $firstM  =  Carbon::now()->format('Ym01');
        $lastM   =  Carbon::now()->format('Ymt');

        $EmpName    = DB::table('LMDBM.dbo.lmEmpDriv AS lmEmpDriv')
                        ->join('LMDBM.dbo.lmCarDriv AS lmCarDriv','lmEmpDriv.EmpDriverCode','lmCarDriv.EmpDriverCode')
                        ->select('lmEmpDriv.EmpDriverCode','lmCarDriv.VehicleCode','lmCarDriv.CarTypeCode','lmEmpDriv.TranspID','lmEmpDriv.EmpDriverCode','lmEmpDriv.EmpDriverTel')
                        ->selectRaw("lmEmpDriv.EmpDriverName + ' ' + lmEmpDriv.EmpDriverLastName AS EmpDriverName")
                        ->selectRaw("(SELECT SUM(res.scoreRate) AS Expr1
                        FROM            LMSRateEmpScore AS res
                        WHERE        (CONVERT(varchar, res.created_time, 112) BETWEEN '$firstM' AND '$lastM') AND (res.empDrivCode = lmEmpDriv.EmpDriverCode)
                        GROUP BY res.empDrivCode) AS SumScoreRate")
                        ->where('lmCarDriv.IsDefault','Y')
                        ->where('lmEmpDriv.Active','Y')
                        ->where('lmEmpDriv.EmpDriverCode',$empCode)
                        ->first();

        $Year = date('Y');
        $RateTitle  = DB::table('LMSRateEmpDriv_Title')
                        ->where('parent',0)
                        ->where('CarType',$EmpName->CarTypeCode)
                        ->where('UseYear',$Year)
                        ->get();
                        
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

        try {
            DB::beginTransaction();

            $RateTitle      = DB::table('LMSRateEmpDriv_Title')
                            ->where('id',$idRateTitle)
                            ->first();

            $SubRateTitle   = DB::table('LMSRateEmpDriv_Title')
                            ->where('id',$idRateSubTitle)
                            ->first();

            $EmpScore['scoreRate']      = $SubRateTitle->Score;
            $EmpScore['mainTitleId']    = $idRateTitle;
            $EmpScore['mainTitleName']  = $RateTitle->Title;
            $EmpScore['subTitleId']     = $idRateSubTitle;
            $EmpScore['subTitleName']   = $SubRateTitle->Title;
            $EmpScore['RateRemark']     = $RateRemark;
            $EmpScore['empDrivCode']    = $EmpCode;
            $EmpScore['created_by']     = Auth::user()->EmpCode;
            $EmpScore['created_time']   = now();

            DB::table('LMSRateEmpScore')->insert($EmpScore);
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

        try {
            DB::beginTransaction();

            if($type == 0){    
                $SumScore = DB::table('LMSRateEmpDriv_Title')->where('parent',0)->where('CarType',$CarType)->where('UseYear',$Year)->Sum('Score');
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

                DB::table('LMSRateEmpDriv_Title')->insert($RateEmpTitle);
                DB::commit();
                
                return 'success';

            }elseif($type == 1){
                $id = $req->id;

                $SumScore = DB::table('LMSRateEmpDriv_Title')
                            ->where('parent',0)
                            ->where('id','<>',$id)
                            ->where('CarType',$CarType)
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

        try {
            DB::beginTransaction();

            $SumScoreMain   = DB::table('LMSRateEmpDriv_Title')->where('id',$MainID)->where('UseYear',$Year)->Sum('Score');    

            if($type == 0){    
            
                $SumScore       = DB::table('LMSRateEmpDriv_Title')->where('Parent',$MainID)->where('UseYear',$Year)->Sum('Score');

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

                DB::table('LMSRateEmpDriv_Title')->insert($RateEmpTitle);
                DB::commit();
                
                return 'success';

            }elseif($type == 1){
                $idSubEdit     = $req->idSubEdit;
                
                $SumScore       = DB::table('LMSRateEmpDriv_Title')->where('Parent',$MainID)->where('id','<>',$idSubEdit)->where('UseYear',$Year)->Sum('Score');

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
