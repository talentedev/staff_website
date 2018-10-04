<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Mail;
use Log;

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
        if (!Auth::guard()->user()->hasRole('super admin')) {
            return redirect('/login');
        }
        // All users
        $users = $this->users->get();

        // All Roles
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users', [
            'users' => $users,
            'roles' => $roles
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
        try {
            $this->users->name = $request->get('name');
            $this->users->email = $request->get('email');
            $this->users->source = $request->get('source');
            $this->users->api_key = $request->get('api_key');
            $this->users->tag = $request->get('tag');
            $this->users->password = \Illuminate\Support\Facades\Hash::make('123456');

            $this->users->save();

            $this->users->assignRole($request->get('role'));

            if($request->get('role') == 'street team') {
                $this->sendMail();
            }

            return response()->json(['status' => true], 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false], 200);
        }
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
        try {
            $user = $this->users::find($id);

            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->source = $request->get('source');
            $user->api_key = $request->get('api_key');
            $user->tag = $request->get('tag');

            $user->save();

            $user->syncRoles($request->get('role'));

            return response()->json(['status' => true], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->users->destroy($id);

        return response()->json(['status' => true], 200);
    }

    // Send mail
    public function sendMail() {

        $data = array(
            'name' => $this->users->name,
            'email' => $this->users->email,
            'password' => '123456'
        );
        Mail::send('mail', $data, function($message) {
            $message->to($this->users->email, $this->users->name)->subject('Staff App Account');
        });
    }
}
