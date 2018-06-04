<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Set the guard for the controller.
     *
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth')->except('logout');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = array('email'=>$email, 'password'=>$password);

        if(!Auth::guard('web')->attempt($credentials)){
            return redirect('/login');
        }else{
            // allow user login except for street team
            if (!Auth::guard('web')->user()->hasRole('street team')) {
                // Register login activity
                $auth_name = Auth::guard('web')->user()->name;
                $auth_id =  Auth::guard('web')->user()->id;
                $log_text = $auth_name . ' logged at ' . date('Y-m-d h:m:s') . '.';
                activity('login')
                    ->causedBy($auth_id)
                    ->log($log_text);

                return redirect('/');
            } else {
                return redirect('/login');
            }
            
        }
    }

    public function logout()
    {
        // Register logout activity
        $auth_name = Auth::guard('web')->user()->name;
        $auth_id = Auth::guard('web')->user()->id;
        $log_text = $auth_name . ' logged out at ' . date('Y-m-d h:m:s') . '.';
        activity('login')
            ->causedBy($auth_id)
            ->log($log_text);

        Auth::guard('web')->logout();
        return redirect('/login');
    }
}
