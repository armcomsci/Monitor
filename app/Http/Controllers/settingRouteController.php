<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class settingRouteController extends Controller
{
    //
    public function RouteProvince(){
        $RegTh = DB::table('LMDBM.dbo.lmRegThai_tm')->get();

        return view('settingRoute.RouteProvince',compact('RegTh'));
    }

    public function GetProvince(){
        $RegProvince = DB::table('LMDBM.dbo.lmProThai_tm as lmProThai_tm')
                        ->join('LMDBM.dbo.lmRegProv_tm as lmRegProv_tm','lmProThai_tm.ProvinceID','lmRegProv_tm.ProvinceID')
                        ->join('LMDBM.dbo.lmRegThai_tm as lmRegThai_tm','lmRegProv_tm.RegionCode','lmRegThai_tm.RegionCode')
                        ->select('lmProThai_tm.*','lmRegThai_tm.RegionName','lmRegThai_tm.RegionCode')
                        ->get();

        return view('settingRoute.Prothai_tm',compact('RegProvince'));
    }

    public function GetAmpthaiTm(){
        $RegAmp = DB::table('LMDBM.dbo.lmAmpThai_tm as lmAmpThai_tm')
                    ->join('LMDBM.dbo.lmAmpProv_tm as lmAmpProv_tm','lmAmpThai_tm.AmpherID','lmAmpProv_tm.AmpherID')
                    ->join('LMDBM.dbo.lmProVince as lmProVince','lmAmpProv_tm.ProvinceID','lmProVince.ProvinceID')
                    ->select('lmAmpThai_tm.*','lmProVince.ProvinceID','lmProVince.ProvinceName')
                    ->get();

        return view('settingRoute.Ampthai_tm',compact('RegAmp'));
    }

    public function RouteBangkok(){
        return view('settingRoute.RouteBangkok');
    }

    public function SubZone(){
        $bkkZone = DB::table('LMDBM.dbo.lmBkkZone_dt_tm as lmBkkZone_dt_tm')
                    ->join('TMDBM.dbo.tmBkkZone as tmBkkZone','lmBkkZone_dt_tm.SubZoneID','tmBkkZone.SubZoneID')
                    ->join('TMDBM.dbo.tmBkkZone as tmBkkZone2','lmBkkZone_dt_tm.SubZoneID_Near','tmBkkZone2.SubZoneID')
                    ->select('lmBkkZone_dt_tm.*','tmBkkZone.Remark as Remark1','tmBkkZone.SubZoneName','tmBkkZone2.SubZoneName as SubZoneName2')
                    ->get();
        return view('settingRoute.RouteBkkSubZone',compact('bkkZone'));
    }

    public function MarZone(){
        $tmBkkZone = DB::table('TMDBM.dbo.tmBkkZone')->get();

        return view('settingRoute.RouteBkkMarZone',compact('tmBkkZone'));
    }

    public function MarToSubZone(){
        $MainToSubZone   =  DB::table('LMDBM.dbo.lmManZone_tm as lmManZone_tm')
                                ->join('TMDBM.dbo.tmBkkMain as tmBkkMain','lmManZone_tm.MainZoneID','tmBkkMain.MainZoneID')
                                ->join('TMDBM.dbo.tmBkkZone as tmBkkZone','lmManZone_tm.SubZoneID','tmBkkZone.SubZoneID')
                                ->select('tmBkkMain.MainZoneName','tmBkkMain.Remark as RemarkMain','tmBkkZone.SubZoneName','tmBkkZone.Remark','lmManZone_tm.MainZoneID','lmManZone_tm.SubZoneID','lmManZone_tm.Priority')
                                ->get();

        return view('settingRoute.RouteBkkMartToSubZone',compact('MainToSubZone'));
    }


    public function RouteTranspot(){
        $TrnZone = DB::table('LMDBM.dbo.lmTrnZone_tm')->get();

        return view('settingRoute.RouteTranspot',compact('TrnZone'));
    }

    public function GetDataGrpTran(){
        $GrpTran = DB::table('LMDBM.dbo.lmGrpTran_tm')->get();

        return view('settingRoute.GetDataGrpTran',compact('GrpTran'));
    }

    public function GetDataCenTran(){
        $Centran = DB::table('LMDBM.dbo.lmCenTran_tm')->get();

        return view('settingRoute.GetDataCenTran',compact('Centran'));
    }
}
