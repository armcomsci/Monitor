<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApproveController extends Controller
{
    //
    public function AppCarDriv(){
        $LogEdit = DB::table('LMSTemp_EditlmCarDriv')
                    ->join('LMSusers','LMSTemp_EditlmCarDriv.created_by','LMSusers.EmpCode')
                    ->select('LMSTemp_EditlmCarDriv.*','LMSusers.Fullname')
                    ->whereNull('confirm_by')
                    ->get();

        return view('approve.cardriv',compact('LogEdit'));
    }

    public function ConfirmEditCar(Request $req){
        DB::beginTransaction();

        $id         = $req->id;
        $status     = $req->status;
        $EmpCode    = Auth::user()->EmpCode;

        try { 

            if($status == 'Y'){
                $DataEdit = DB::table('LMSTemp_EditlmCarDriv')->where('id',$id)->first();

                $data = json_decode($DataEdit->data_update,true);
 

                $selectedData = ['CarTypeCode','ProvinceID','CarBrandCode','CarSerieCode','OilTypeCode','FormulaCode','CarComp','CoFormulaCode','EmpDriv','InsureTypeCode','InsureCompCode','InsureTypeCode_Form','InsureCompCode_Form','FinanceCode'];

                $dateRangeData = ['InsureStart_End_Date','Insure_Form_Start_End_Date','CarFin_Start_End_Date','CompCar_Start_End_Date'];


                $lmCarDetail = ['CarTypeCode','ProvinceID','CarBrandCode','CarSerieCode','RegistDate','Year','Color','VehicleNo','BodyNo','OilTypeCode','FormulaCode','GPS','Remark'];

                $lmCoDriver  = ['CoFormulaCode','CoTel','CoEmpName','CarComp'];
                
                $lmCarDriv   = ['EmpDrivCode'];

                $lmCarInsure = ['InsureStart_End_Date','PolicyInsureNo','InsureTypeCode','InsureCompCode','InsureCap','InsureValue'];

                $lmCarForm   = ['FormulateNO','InsureTypeCode_Form','InsureCompCode_Form','Insure_Form_Start_End_Date','FormulateValue'];

                $lmCarFinan  = ['FinanceCode','ContractNo','CarValue','CarTotalValue','CarDownValue','CarInterest','CarLoanValue','CarInstallmentValue','CarInstallment','CarInstallmentLeft','CarBalanceValue','CarFin_Start_End_Date','CarFinRemark','CarFinStatus'];

                $lmCarComp   = ['CompCarValue','CompCarTotal','CompDownValue','CompCarInterest','CompCarLoan','CompInstallmentValue','CompInstallment','CompInstallmentLeft','CompCarBalance','CompCar_Remark','CarCompStatus'];
                
                $update_lmCarDetail = [];
                $update_lmCoDriver  = [];
                $update_lmCarDriv   = [];
                $update_lmCarInsure = [];
                $update_lmCarForm   = [];
                $update_lmCarFinan  = [];
                $update_lmCarComp   = [];

                foreach ($data as $key => $value) {
    
                    $name = $value['name'];

                    $val  = $value['val'];
                    
                    if (in_array($name,$selectedData)) {
                        $val  =  $this->explodeVal($value['val']);
                    }

                    if (in_array($name,$lmCarDetail)) {
                        if($name == "RegistDate"){
                            $val = Carbon::createFromFormat('d/m/Y',$val)->format('Y-m-d');
                        }

                        $update_lmCarDetail[$name] = $val;

                    } elseif (in_array($name,$lmCoDriver))  {
                        if($name == "CoFormulaCode"){
                            $update_lmCoDriver['FormulaCode'] = $val;
                        }else{
                            $update_lmCoDriver[$name] = $val;
                        }

                    } elseif (in_array($name,$lmCarDriv))  {
                            $update_lmCarDriv[$name] = $val;

                    } elseif (in_array($name,$lmCarInsure))  {
                        if($name == "InsureStart_End_Date"){
                            $dateRange  = explode(' - ',$val);

                            $Insure_date_start      = $dateRange[0];
                            $Stamp_date_start       = Carbon::createFromFormat('d/m/Y', $Insure_date_start)->format('Y-m-d');
                    
                            $Insure_date_end        = $dateRange[1];
                            $Stamp_date_end         = Carbon::createFromFormat('d/m/Y', $Insure_date_end)->format('Y-m-d');

                            $update_lmCarInsure['DateStart']         = $Stamp_date_start;
                            $update_lmCarInsure['DateEnd']           = $Stamp_date_end;
                        }else{
                            $update_lmCarInsure[$name] = $val;
                        }
                    } elseif (in_array($name,$lmCarForm))  {
                        if($name == "Insure_Form_Start_End_Date"){
                            $dateRange  = explode(' - ',$val);

                            $Insure_Form_date_start      = $dateRange[0];
                            $Stamp_date_start            = Carbon::createFromFormat('d/m/Y', $Insure_Form_date_start)->format('Y-m-d');
                    
                            $Insure_Form_date_end        = $dateRange[1];
                            $Stamp_date_end              = Carbon::createFromFormat('d/m/Y', $Insure_Form_date_end)->format('Y-m-d');

                            $update_lmCarForm['DateStart']         = $Stamp_date_start;
                            $update_lmCarForm['DateEnd']           = $Stamp_date_end;
                        }else{
                            if($name == "InsureTypeCode_Form"){
                                $update_lmCarForm['InsureTypeCode'] = $val;
                            }
                            elseif($name == "InsureCompCode_Form"){
                                $update_lmCarForm['InsureCompCode'] = $val;
                            }else{
                                $update_lmCarForm[$name] = $val;
                            }
                        }

                    } elseif (in_array($name,$lmCarFinan))  {
                        if($name == "CarFin_Start_End_Date"){
                            $dateRange  = explode(' - ',$val);

                            $CarFin_date_start      = $dateRange[0];
                            $Stamp_date_start            = Carbon::createFromFormat('d/m/Y', $CarFin_date_start)->format('Y-m-d');
                    
                            $CarFin_date_end        = $dateRange[1];
                            $Stamp_date_end              = Carbon::createFromFormat('d/m/Y', $CarFin_date_end)->format('Y-m-d');

                            $update_lmCarFinan['DateStart']         = $Stamp_date_start;
                            $update_lmCarFinan['DateEnd']           = $Stamp_date_end;
                        }else{
                            if($name == "CarFinStatus"){
                                $update_lmCarFinan['Status'] = $val;
                            }
                            elseif($name == "CarFinRemark"){
                                $update_lmCarFinan['Remark'] = $val;
                            }else{
                                $update_lmCarFinan[$name] = $val;
                            }
                        }
                    } elseif (in_array($name,$lmCarComp))  {
                        if($name == "CompCar_Start_End_Date"){
                            $dateRange  = explode(' - ',$val);

                            $CompCar_date_start          = $dateRange[0];
                            $Stamp_date_start            = Carbon::createFromFormat('d/m/Y', $CompCar_date_start)->format('Y-m-d');
                    
                            $CompCar_date_end            = $dateRange[1];
                            $Stamp_date_end              = Carbon::createFromFormat('d/m/Y', $CompCar_date_end)->format('Y-m-d');

                            $update_lmCarComp['DateStart']         = $Stamp_date_start;
                            $update_lmCarComp['DateEnd']           = $Stamp_date_end;
                        }else{
                            if($name == "CarCompStatus"){
                                $update_lmCarComp['Status'] = $val;
                            }elseif($name == "CompCar_Remark"){
                                $update_lmCarComp['Remark'] = $val;
                            }else{
                                $update_lmCarComp[$name] = $val;
                            }
                        }
                    }
                }
                // dd($update_lmCarDetail,$update_lmCoDriver,$update_lmCarDriv,$update_lmCarInsure,$update_lmCarForm,$update_lmCarFinan,$update_lmCarComp);
                $tableUpdate = '';

                if(count($update_lmCarDetail) > 0){
                
                    DB::table('LMDBM.dbo.lmCarDetail')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCarDetail);

                }
                if(count($update_lmCoDriver) > 0){

                    DB::table('LMDBM.dbo.lmCoDriver')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCoDriver);

                }
                if(count($update_lmCarDriv) > 0){

                    DB::table('LMDBM.dbo.lmCarDriv')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCarDriv);

                }
                if(count($update_lmCarInsure) > 0){

                    DB::table('LMDBM.dbo.lmCarInsure')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCarInsure);

                }
                if(count($update_lmCarForm) > 0){

                    DB::table('LMDBM.dbo.lmCarForm')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCarForm);

                }
                if(count($update_lmCarFinan) > 0){

                    DB::table('LMDBM.dbo.lmCarFinan')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCarFinan);

                }
                if(count($update_lmCarComp) > 0){

                    DB::table('LMDBM.dbo.lmCarComp')->where('VehicleCode',$DataEdit->vehicleCode)->update($update_lmCarComp);

                }

                DB::table('LMSTemp_EditlmCarDriv')
                    ->where('id',$id)
                    ->update(
                        [
                            'status_confirm' => 'Y',
                            'confirm_by' => $EmpCode,
                            'confirm_time' => now()
                        ]
                    );

                DB::commit();
                
                return 'success';

            }else if($status == 'N'){
                DB::table('LMSTemp_EditlmCarDriv')
                    ->where('id',$id)
                    ->update(
                        [
                            'status_confirm' => 'R',
                            'confirm_by' => $EmpCode,
                            'confirm_time' => now()
                        ]
                    );

                DB::commit();

                return 'success';
            }

        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    private function explodeVal($text){
        $Array_text = explode(":",$text);
        return $Array_text[0];
    }
}
