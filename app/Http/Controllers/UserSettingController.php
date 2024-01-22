<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    //
    public function settingUser(){
        $EmpDrive = DB::table('LMDBM.dbo.lmEmpDriv as empDrive')
                    ->leftjoin('DTDBM.dbo.EMTransp as emTrans','emTrans.TranspID','empDrive.TranspID')
                    ->leftjoin('LMDBM.dbo.lmEmpGrop as lmEmpGrop','lmEmpGrop.EmpGroupCode','empDrive.EmpGroupCode')
                    ->select('empDrive.*','emTrans.TranspName','lmEmpGrop.EmpGroupName')
                    ->get();

        $EmpTrans = DB::table('DTDBM.dbo.EMTransp')->where('Remark',1)->get();

        return view('settingUser.settingUser',compact('EmpDrive','EmpTrans'));
    }

    public function settingGet(Request $req){
        $empCode = $req->id;
        $data = DB::table('LMDBM.dbo.lmEmpDriv')->where('EmpDriverCode',$empCode)->first();

        return response()->json($data,200);
    }

    public function settingSave(Request $req){
        $EmpDriverCode          = $req->EmpDriverCode;
        $EmpDriverCardID        = $req->EmpDriverCardID;
        $EmpGroupCode           = $req->EmpGroupCode;
        $EmpDriverName          = $req->EmpDriverName;
        $EmpDriverLastName      = $req->EmpDriverLastName;
        $EmpDriverTel           = $req->EmpDriverTel;
        $TranspID               = $req->TranspID;
        $BankNO                 = $req->BankNO;
        $SavingValue            = $req->SavingValue;
        $PercentType            = $req->PercentType;
        $SavingPercent          = $req->SavingPercent;
        $SavingAmount           = $req->SavingAmount;
        $IsSaving               = $req->IsSaving;
        $EmpDriverRemark        = $req->EmpDriverRemark;

        $type = $req->type;

        try {
            if($type == 0){
                $checkGroup = DB::table('LMDBM.dbo.lmEmpDriv')->where('EmpDriverCode',$EmpDriverCode)->count();
                if($checkGroup >= 1){
                    return 'error';
                }else{
                    $EmpDrive['EmpDriverCode']      = $EmpDriverCode;
                    $EmpDrive['EmpDriverCardID']    = $EmpDriverCardID;
                    $EmpDrive['EmpGroupCode']       = $EmpGroupCode;
                    $EmpDrive['EmpDriverName']      = $EmpDriverName;
                    $EmpDrive['EmpDriverLastName']  = $EmpDriverLastName;
                    $EmpDrive['EmpDriverTel']       = $EmpDriverTel;
                    $EmpDrive['TranspID']           = $TranspID;
                    $EmpDrive['BankNO']             = $BankNO;
                    $EmpDrive['SavingValue']        = $SavingValue;

                    if($IsSaving == "Y"){
                        $EmpDrive['IsSaving']   = $IsSaving;
                    }else{
                        $EmpDrive['IsSaving']   = "N";
                    }
                    if($PercentType == "P"){
                        $EmpDrive['SavingPercent']            = $SavingPercent;
                    }elseif($PercentType == "B"){
                        $EmpDrive['SavingAmount']             = $SavingAmount;
                    }
                    $EmpDrive['EmpDriverRemark'] = $EmpDriverRemark;

                    DB::table('LMDBM.dbo.lmEmpDriv')->insert($EmpDrive);

                    return 'success';
                }
                
            }elseif($type == 1){
                $EmpDrive['EmpGroupCode']       = $EmpGroupCode;
                $EmpDrive['EmpDriverName']      = $EmpDriverName;
                $EmpDrive['EmpDriverLastName']  = $EmpDriverLastName;
                $EmpDrive['EmpDriverTel']       = $EmpDriverTel;
                $EmpDrive['TranspID']           = $TranspID;
                $EmpDrive['BankNO']             = $BankNO;
                $EmpDrive['SavingValue']        = $SavingValue;

                if($IsSaving == "Y"){
                    $EmpDrive['IsSaving']   = $IsSaving;
                }else{
                    $EmpDrive['IsSaving']   = "N";
                }
                if($PercentType == "P"){
                    $EmpDrive['SavingPercent']            = $SavingPercent;
                }elseif($PercentType == "B"){
                    $EmpDrive['SavingAmount']             = $SavingAmount;
                }
                $EmpDrive['EmpDriverRemark'] = $EmpDriverRemark;

                DB::table('LMDBM.dbo.lmEmpDriv')->where('EmpDriverCode',$EmpDriverCode)->update($EmpDrive);

                return 'success';
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function settingChange(Request $req){
        $empCode = $req->id;
        $Status    = $req->status;
        
        try {
            $data = DB::table('LMDBM.dbo.lmEmpDriv')->where('EmpDriverCode',$empCode)->update(['Active'=>$Status]);
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
