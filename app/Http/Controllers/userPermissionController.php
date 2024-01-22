<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class userPermissionController extends Controller
{
    public function index(){
        $EmpAdmin = DB::table('LMSusers')->select('EmpCode','Fullname')->get();

        return view('permission.userSetPer',compact('EmpAdmin'));
    }

    public function getPerMission($menu_id){

        $User = DB::table('LMSmenu_Permission')->select('EmpCode')->where('Menu_id',$menu_id)->get();

        return response()->json($User, 200);
    }

    public function createUser(){
        $lmUser = DB::table('LMSusers')->get();

        return view('permission.userCreate',compact('lmUser'));
    }

    public function changeStatusUser(Request $req){
        DB::beginTransaction();
        $UserId = $req->id;
        $Status    = $req->status;
        try {
            $data = DB::table('LMSusers')->where('id',$UserId)->update(['status'=>$Status]);
            DB::commit();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function userGet(Request $req){
        $id = $req->id;

        $User = DB::table('LMSusers')->where('id',$id)->first();

        return response()->json($User, 200);
    }

    public function saveUser(Request $req){
        DB::beginTransaction();

        $empcode  = $req->EmpCode;
        $Fullname = $req->Fullname;
        $password = Hash::make($req->password);
        $Flag     = $req->Flag;
        $type     = $req->type;

        try { 
            if($type == 0){
                $checkUser = DB::table('LMSusers')->where('EmpCode',$empcode)->count();
                if($checkUser >= 1){
                    return 'Added';
                }else{
                    $EmpUser['EmpCode']      = $empcode;
                    $EmpUser['Fullname']     = $Fullname;
                    $EmpUser['password']     = $password;
                    if($Flag == 1){
                        $EmpUser['type']         = $Flag;
                    }   
                    $EmpUser['created_at'] = now();
                    $EmpUser['updated_at'] = now();
                    
                    DB::table('LMSusers')->insert($EmpUser);
                    DB::commit();
                    return 'success';
                }
            }elseif($type == 1){
                $EmpUser['EmpCode']      = $empcode;
                $EmpUser['Fullname']     = $Fullname;
                if($req->password != ''){
                    $EmpUser['password']     = $password;
                }
                if($Flag == 1){
                    $EmpUser['type']         = $Flag;
                }   
                $EmpUser['updated_at'] = now();

                DB::table('LMSusers')->where('EmpCode',$empcode)->update($EmpUser);
                DB::commit();
                return 'success';
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function save(Request $req){
        DB::beginTransaction();

        $Sub_Menu_Id = $req->Sub_Menu_Id;
        $EmpCode     = $req->EmpCode;

        try {

            DB::table('LMSmenu_Permission')->where('Menu_id',$Sub_Menu_Id)->delete();

            foreach ($EmpCode as $key => $value) {

                $PerMission['EmpCode'] = $value;
                $PerMission['Menu_id'] = $Sub_Menu_Id;

                DB::table('LMSmenu_Permission')->insert($PerMission);
            }

            DB::commit();
            return 'success';

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
       
    }
}
