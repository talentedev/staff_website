<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

class SettingController extends Controller
{
     /**
     * @var User
     */
    private $user;

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
    public function __construct(User $user)
    {
        $this->middleware('auth:web');
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Change the account info.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeMe(Request $request)
    {
        $me = $this->user::find(Auth::user()->id);

        $me->name = $request->get('name');
        $me->email = $request->get('email');
        $password = $request->get('password');
        if ($password != '') {
            $me->password = \Illuminate\Support\Facades\Hash::make($request->get('password'));
        }

        $me->save();
        return response()->json(['status' => true], 200);
    }
}
