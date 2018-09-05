<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class EmailController extends Controller
{
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
        $this->middleware('auth:web');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = DB::table('settings')->get();

        return view('admin.emails')
                    ->with('settings', $settings);
    }

    /**
     * Update status email settings
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatusEmail(Request $request)
    {
        $ship_update_email = $request->get('ship_update_email');
        $sales_update_email = $request->get('sales_update_email');
        $account_update_email = $request->get('account_update_email');
        $swab_update_email = $request->get('swab_update_email');
        $sequence_update_email = $request->get('sequence_update_email');

        DB::table('settings')->where('setting_key', 'ship_update_email')->update(['setting_value' => $ship_update_email == true ? '1' : '0']);
        DB::table('settings')->where('setting_key', 'sales_update_email')->update(['setting_value' => $sales_update_email == true ? '1' : '0']);
        DB::table('settings')->where('setting_key', 'account_update_email')->update(['setting_value' => $account_update_email == true ? '1' : '0']);
        DB::table('settings')->where('setting_key', 'swab_update_email')->update(['setting_value' => $swab_update_email == true ? '1' : '0']);
        DB::table('settings')->where('setting_key', 'sequence_update_email')->update(['setting_value' => $sequence_update_email == true ? '1' : '0']);

        return response()->json(['status' => true], 200);
    }

    /**
     * Update reminder email settings
     *
     * @return \Illuminate\Http\Response
     */
    public function updateReminderEmail(Request $request)
    {
        $first_reminder_email = $request->get('first_reminder_email');
        $second_reminder_email = $request->get('second_reminder_email');

        if ($first_reminder_email != '') {
            DB::table('settings')->where('setting_key', 'first_reminder_email')->update(['setting_value' => $first_reminder_email]);
        }

        if ($second_reminder_email != '') {
            DB::table('settings')->where('setting_key', 'second_reminder_email')->update(['setting_value' => $second_reminder_email]);
        }

        return response()->json(['status' => true], 200);
    }
}
