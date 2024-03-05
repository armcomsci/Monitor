<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class leaveWorkController extends Controller
{
    public function leaveWork(){
        $leave = DB::table('LMSLeaveWork')->get();

        return view('leaveWork.leaveWork',compact('leave'));
    }

    public function save(Request $req){
        $leave_name      = $req->leave_name;
        $leave_date      = $req->leave_date;
        $type            = $req->type;
        $Port            = Auth::user()->EmpCode;
        try {
            if($type == 0){
             
                    $Leave['leave_name']         = $leave_name;
                    $Leave['leave_limit_date']   = $leave_date;
                    $Leave['created_by']         = $Port;
                    $Leave['created_time']       = now();
                    
                    DB::table('LMSLeaveWork')->insert($Leave);
    
                    return 'success';

            }elseif($type == 1){
                $idEdit = $req->idEdit;

                $Leave['leave_name']         = $leave_name;
                $Leave['leave_limit_date']   = $leave_date;
                $Leave['updated_by']         = $Port;
                $Leave['updated_time']       = now();

                DB::table('LMSLeaveWork')->where('id',$idEdit)->update($Leave);

                return 'success';
            }
          
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function getData(Request $req){
        $id     = $req->id;
        $data   = DB::table('LMSLeaveWork')->where('id',$id)->first();

        return response()->json($data,200);
    }

    public function del(Request $req){
        $id = $req->id;
        try {
            $data = DB::table('LMSLeaveWork')->where('id',$id)->delete();
            return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
