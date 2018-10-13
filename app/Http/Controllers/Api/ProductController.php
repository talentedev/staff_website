<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Product;
use Spatie\Activitylog\Models\Activity;
use Log;
use DB;
use Mail;
use Carbon\Carbon;
use App\Mail\StatusReminder;
use App\Mail\StatusUpdated;

class ProductController extends ApiController
{
    /**
     * @var Task
     */
    private $product;

    /**
     * TaskController constructor.
     *
     * @param Task $task
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try{

            if($request->get('pheramor_id') != null) {
                $this->product->pheramor_id = $request->get('pheramor_id');
            }

            if($request->get('sales_email') != null) {
                $this->product->sales_email = $request->get('sales_email');
            }

            if($request->get('first_name') != null) {
                $this->product->first_name = $request->get('first_name');
            }

            if($request->get('last_name') != null) {
                $this->product->last_name= $request->get('last_name');
            }

            if($request->get('phone') != null) {
                $this->product->phone = $request->get('phone');
            }

            $this->product->sales_date = \Carbon\Carbon::now();
            $this->product->source = Auth::user()->roles->pluck('name')[0];

            // Create new contact on admin system
            $this->product->save();

            // Send email to user
            $this->sendMail($this->product, 'sales_update');

            return $this->respond([
                'status' => true,
                'data'   => $this->product->orderBy('updated_at', 'desc')->first()
            ]);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->respond([
                'status' => false,
                'message' => 'Pheramor ID or Email already exist'
            ]);
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
        $product = $this->product::where('pheramor_id', $id)->get()->first();
        return $this->respond([
            'status' => true,
            'data'   => $product
        ]);
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
        $validator = Validator::make($request->all(), [
            'sales_date' => 'date',
            'ship_date' => 'date',
            'account_connected_date' => 'date',
            'swab_returned_date' => 'date',
            'ship_to_lab_date' => 'date',
            'lab_received_date' => 'date',
            'sequenced_date' => 'date',
            'uploaded_to_server_date' => 'date',
            'bone_marrow_consent_date' => 'date',
            'bone_marrow_shared_date' => 'date'
        ]);

        if ($validator->fails()) {
            return $this->respond([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }
        try{
            $product = $this->product::where('pheramor_id', $id)->get()->first();

            if($request->get('first_name') != null) {
                $product->first_name = $request->get('first_name');
            }

            if($request->get('last_name') != null) {
                $product->last_name = $request->get('last_name');
            }

            if($request->get('sales_email') != null) {
                $product->sales_email = $request->get('sales_email');
            }

            if($request->get('account_email') != null) {
                $product->account_email = $request->get('account_email');
            }

            if($request->get('phone') != null) {
                $product->phone = $request->get('phone');
            }

            if($request->get('sales_date') != null) {
                $product->sales_date = $request->get('sales_date');
                $this->sendMail($product, 'sales_update');
            }

            if($request->get('ship_date') != null) {
                $product->ship_date = $request->get('ship_date');
                $this->sendMail($product, 'ship_update');
                $this->prepareReminderEmail($product->id);
            }
            
            if($request->get('account_connected_date') != null) {
                $product->account_connected_date = $request->get('account_connected_date');
                $this->sendMail($product, 'account_update');
            }

            if($request->get('swab_returned_date') != null) {
                $product->swab_returned_date = $request->get('swab_returned_date');
                $this->sendMail($product, 'swab_update');
            }

            if($request->get('ship_to_lab_date') != null) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
            }

            if($request->get('lab_received_date') != null) {
                $product->lab_received_date = $request->get('lab_received_date');
            }

            if($request->get('sequenced_date') != null) {
                $product->sequenced_date = $request->get('sequenced_date');
                $this->sendMail($product, 'sequenced_update');
            }

            if($request->get('uploaded_to_server_date') != null) {
                $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
            }

            if($request->get('bone_marrow_consent_date') != null) {
                $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
            }

            if($request->get('bone_marrow_shared_date') != null) {
                $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
            }

            $product->save();

            return $this->respond([
                'status' => true,
                'data'   => $this->product->orderBy('updated_at', 'desc')->first()
            ]);
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->respond([
                'status' => false,
                'message' => 'Pheramor ID dont exist or same sales email exist already'
            ]);
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
        //
    }

    /**
     * Update the phone number in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePhone(Request $request)
    {
        try {
            $product = $this->product::where('pheramor_id', $request->get('pheramor_id'))->get()->first();
            $product->phone = $request->get('phone');
            $product->save();
            return $this->respond([
                'status' => true,
                'data'   => $this->product->orderBy('updated_at', 'desc')->first()
            ]);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return $this->respond([
                'status' => false,
                'message' => 'We can not update phone number.'
            ]);
        }
    }

        /**
     * Send email and log the status when user update status date.
     *
     * @param  App\Product  $product
     * @param  String  $type
     * @return void
     */
    protected function sendMail($product, $type) {

        $to = $product->sales_email;
        $cc = $product->account_email;
        $data = array(
                    'name' => $product->first_name,
                    'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $product->pheramor_id,
                    'to' => $product->sales_email
                );

        if ($type == 'sales_update') {
            $data['kit_id'] = $product->pheramor_id;
        }

        // Check if sending email is allowed.
        switch ($type) {
            case 'sales_update':
                $setting_key = 'sales_update_email';
                $log_prefix = 'Sales update email';
                break;
            case 'ship_update':
                $setting_key = 'ship_update_email';
                $log_prefix = 'Ship update email';
                break;
            case 'account_update':
                $setting_key = 'account_update_email';
                $log_prefix = 'Account connected email';
                break;
            case 'swab_update':
                $setting_key = 'swab_update_email';
                $log_prefix = 'Swab return email';
                break;
            case 'sequenced_update':
                $setting_key = 'sequence_update_email';
                $log_prefix = 'Sequenced email';
                break;
            default:
                break;
        }

        $isEnabled = DB::table('settings')->where('setting_key', $setting_key)->get()->first()->setting_value;

        // Send email if it is allowed
        if ($isEnabled) {
            if ($cc == null || $cc == '') {
                Mail::to($to)->queue(new StatusUpdated($data, $type));
                $log_success_text = $log_prefix . ' successfuly sent to ' . $product->sales_email . ' by ' . Auth::user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
                $log_fail_text = $log_prefix . ' can not send to ' . $product->sales_email . ' by ' . Auth::user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
            } else {
                Mail::to($to)
                    ->cc($cc)
                    ->queue(new StatusUpdated($data, $type));
                $log_success_text = $log_prefix . ' successfuly sent to ' . $product->sales_email . ', ' . $product->account_email . ' by ' . Auth::user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
                $log_fail_text = $log_prefix . ' can not send to ' . $product->sales_email . ', ' . $product->account_email . ' by ' . Auth::user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
            }

            // Log status whether email sent successfuly or not.
            if( count(Mail::failures()) > 0 ) {
                activity('mail')
                    ->causedBy(Auth::user()->id)
                    ->log( $log_fail_text );

            } else {
                activity('mail')
                    ->causedBy(Auth::user()->id)
                    ->log( $log_success_text );
            }
        }
    }

    /**
     * Save the data for reminder emailing.
     *
     * @param  Integer  $product_id
     * @return void
     */
    protected function prepareReminderEmail($product_id) {
        // Save data to email queue
        $firstInverval = (int)DB::table('settings')->where('setting_key', 'first_reminder_email')->get()->first()->setting_value;
        $secondInverval = (int)DB::table('settings')->where('setting_key', 'second_reminder_email')->get()->first()->setting_value;
        $current = Carbon::now();

        DB::table('email_queue')->insert([
            'product_id' => $product_id,
            'send_order' => 1,
            'send_date' => $current->addDays($firstInverval)//->addMinutes($firstInverval)
        ]);
        DB::table('email_queue')->insert([
            'product_id' => $product_id,
            'send_order' => 2,
            'send_date' => $current->addDays($secondInverval - $firstInverval)//->addMinutes($secondInverval - $firstInverval)
        ]);
    }

    // Send test mail.
    public function sendTestMail() {
        try{
            $currentStart = Carbon::now()->format('Y-m-d H:i:00');//->toDateTimeString();
            $currentEnd = Carbon::now()->format('Y-m-d H:i:59');//->toDateTimeString();
            $emails = DB::table('email_queue')->get();
            
            foreach ($emails as $key => $value) {
                $product = DB::table('products')->find($value->product_id);
                $email_data = array(
                    'name' => $product->first_name,
                    'link' => 'https://id.pheramor.com/status.php?pheramor_id=',
                    'to' => 'markozzz37@gmail.com'//$product->sales_email
                );
                if ($value->send_order == 1) {
                    // $this->sendMail($product->sales_email, $product->account_email, $email_data, 'ship_update');
                    Mail::to('markozzz37@gmail.com')->queue(new StatusReminder($email_data, 'ship_reminder1'));
                    // DB::table('email_queue')->where('id', $value->id)->delete();
                } else {
                    // $this->sendMail($product->sales_email, $product->account_email, $email_data, 'ship_update');
                    Mail::to('markozzz37@gmail.com')->queue(new StatusReminder($email_data, 'ship_reminder2'));
                    // DB::table('email_queue')->where('id', $value->id)->delete();
                }
            }
            return $this->respond($emails);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return $this->respond([
                'message' => $e->getMessage()
            ]);
        }
    }
}
