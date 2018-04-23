<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * @var User
     */
    private $users;

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
        $this->users = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->users->get();
        return view('admin.users', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = '';

        if($this->existSameCode($request->get('source'))) {
            $message = 'Access Code exist already';
        } else {
            $this->users->name = $request->get('name');
            $this->users->email = $request->get('email');
            $this->users->source = $request->get('source');
            $this->users->api_key = $request->get('api_key');
            $this->users->tag = $request->get('tag');
            $this->users->password = \Illuminate\Support\Facades\Hash::make("123456");

            $this->users->save();
            $message = 'User successfully added';
        }

        return response()->json(['message' => $message], 200);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = '';

        $user = $this->users::find($id);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->source = $request->get('source');
        $user->api_key = $request->get('api_key');
        $user->tag = $request->get('tag');
        $user->password = \Illuminate\Support\Facades\Hash::make("123456");

        $user->save();
        $message = 'User successfully updated';

        return response()->json(['message' => $message], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Check if same access code exist.
     *
     * @param  string $access_code
     * @return Boolean
     */
    public function existSameCode($access_code)
    {
        $is_exist = false;
        $user = $this->users::where('source', $access_code)->count();
        if($user > 0) {
            $is_exist = true;
        }
        return $is_exist;
    }
}
