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
use Carbon\Carbon;
use App\Mail\StatusReminder;
use Illuminate\Support\Facades\Mail;

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
            $isEnabled = DB::table('settings')->where('setting_key', 'sales_update_email')->get()->first()->setting_value;
            if ($isEnabled == '1') {
                $email_data = array(
                    'name' => $this->product->first_name,
                    'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $request->get('pheramor_id'),
                    'kit_id' => $request->get('pheramor_id'),
                    'to' => $this->product->sales_email
                );
                $this->sendMail($this->product->sales_email, null, $email_data, 'sales_update');
            }

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
                $isEnabled = DB::table('settings')->where('setting_key', 'sales_update_email')->get()->first()->setting_value;
                if ($isEnabled == '1') {
                    // Send email
                    $email_data = array(
                        'name' => $product->first_name,
                        'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $id,
                        'kit_id' => $request->get('pheramor_id'),
                        'to' => $product->sales_email
                    );
                    $this->sendMail($product->sales_email, $product->account_email, $email_data, 'sales_update');
                }
            }

            if($request->get('ship_date') != null) {
                $product->ship_date = $request->get('ship_date');
                $isEnabled = DB::table('settings')->where('setting_key', 'ship_update_email')->get()->first()->setting_value;
                if ($isEnabled == '1') {
                    // Send email
                    $email_data = array(
                        'name' => $product->first_name,
                        'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $id,
                        'to' => $product->sales_email
                    );
                    $this->sendMail($product->sales_email, $product->account_email, $email_data, 'ship_update');

                    // Save data to email queue
                    $firstInverval = (int)DB::table('settings')->where('setting_key', 'first_reminder_email')->get()->first()->setting_value;
                    $secondInverval = (int)DB::table('settings')->where('setting_key', 'second_reminder_email')->get()->first()->setting_value;
                    $current = Carbon::now();

                    DB::table('email_queue')->insert([
                        'product_id' => $product->id,
                        'send_order' => 1,
                        'send_date' => $current->addMinutes($firstInverval)//->addDays($firstInverval)
                    ]);
                    DB::table('email_queue')->insert([
                        'product_id' => $product->id,
                        'send_order' => 2,
                        'send_date' => $current->addMinutes($secondInverval - $firstInverval)//->addDays($secondInverval - $firstInverval)
                    ]);
                }
            }
            
            if($request->get('account_connected_date') != null) {
                $product->account_connected_date = $request->get('account_connected_date');
                $isEnabled = DB::table('settings')->where('setting_key', 'account_update_email')->get()->first()->setting_value;
                if ($isEnabled == '1') {
                    // Send email
                    $email_data = array(
                        'name' => $product->first_name,
                        'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $id,
                        'to' => $product->sales_email
                    );
                    $this->sendMail($product->sales_email, $product->account_email, $email_data, 'account_update');
                }
            }

            if($request->get('swab_returned_date') != null) {
                $product->swab_returned_date = $request->get('swab_returned_date');
                $isEnabled = DB::table('settings')->where('setting_key', 'swab_update_email')->get()->first()->setting_value;
                if ($isEnabled == '1') {
                    // Send email
                    $email_data = array(
                        'name' => $product->first_name,
                        'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $id,
                        'to' => $product->sales_email
                    );
                    $this->sendMail($product->sales_email, $product->account_email, $email_data, 'swab_update');
                }
            }

            if($request->get('ship_to_lab_date') != null) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
            }

            if($request->get('lab_received_date') != null) {
                $product->lab_received_date = $request->get('lab_received_date');
            }

            if($request->get('sequenced_date') != null) {
                $product->sequenced_date = $request->get('sequenced_date');
                $isEnabled = DB::table('settings')->where('setting_key', 'sequence_update_email')->get()->first()->setting_value;
                if ($isEnabled == '1') {
                    // Send email
                    $email_data = array(
                        'name' => $product->first_name,
                        'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $id,
                        'to' => $product->sales_email
                    );
                    $this->sendMail($product->sales_email, $product->account_email, $email_data, 'sequenced_update');
                }
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

    // Send mail
    public function sendTestMail() {
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
                Mail::to($product->sales_email)->queue(new StatusReminder($email_data, 'ship_reminder1'));
                // DB::table('email_queue')->where('id', $value->id)->delete();
            } else {
                // $this->sendMail($product->sales_email, $product->account_email, $email_data, 'ship_update');
                Mail::to($product->sales_email)->queue(new StatusReminder($email_data, 'ship_reminder2'));
                // DB::table('email_queue')->where('id', $value->id)->delete();
            }
        }
        return $this->respond($emails);
    }
}
