<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckRememberToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        // dd(Auth::user()->getRememberToken(),Auth::check(), is_null(Auth::user()->getRememberToken()) );
        if (Auth::check() && Auth::user()->getRememberToken() == "" ) {
            Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            return redirect('Login');
        }else{
            $curentUrl    = str_replace(url('/'), '', url()->previous());
            $EmpCode      = Auth::user()->EmpCode;
            $urlEx        = array('/GpsCarAll','/Login','/Logout','/');

            if(!in_array($curentUrl,$urlEx)){

                $CheckPerMiss = DB::table('LMSmenu')
                                ->join('LMSmenu_Permission','LMSmenu.id','LMSmenu_Permission.Menu_id')
                                ->where('LMSmenu.menuUrl',$curentUrl)
                                ->where('LMSmenu_Permission.EmpCode',$EmpCode)
                                ->count();

                if($CheckPerMiss == 0){
                    abort(403,'รหัสของคุณไม่มีสิทธิ์ในเข้าถึงหน้าดังกล่าว'.$curentUrl);
                }
            }
            
        }
        return $next($request);
    }
}
