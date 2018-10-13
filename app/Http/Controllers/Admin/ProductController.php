<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Product;
use Spatie\Activitylog\Models\Activity;
use Mail;
use App\Mail\StatusUpdated;
use Log;
use DB;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * @var User
     */
    private $product;

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
    public function __construct(Product $product)
    {
        $this->middleware('auth:web');
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guard()->user()->hasRole('street team')) {
            return redirect('/login');
        }
        $products = $this->product->get();
        return view('admin.products', [
            'products' => $products
        ]);
    }

    /**
     * Return a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        //define index of column
        $columns = array( 
            0 =>'id',
            1 =>'pheramor_id', 
            2 => 'sales_email',
            3 => 'account_email',
            4 => 'first_name',
            5 => 'last_name',
            6 => 'phone',
            7 => 'source',
            8 => 'sales_date',
            9 => 'ship_date',
            10 => 'account_connected_date',
            11 => 'swab_returned_date',
            12 => 'ship_to_lab_date',
            13 => 'lab_received_date',
            14 => 'sequenced_date',
            15 => 'uploaded_to_server_date',
            16 => 'bone_marrow_consent_date',
            17 => 'bone_marrow_shared_date',
            18 => 'note'
        );

        $totalData = Product::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // Total search
        if(empty($request->input('search.value')))
        {            
            // $posts = Product::offset($start)
            //              ->limit($limit)
            //              ->orderBy($order,$dir)
            //              ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  Product::where('id','LIKE',"%{$search}%")
                            ->orWhere('pheramor_id', 'LIKE',"%{$search}%")
                            ->orWhere('sales_email', 'LIKE',"%{$search}%")
                            ->orWhere('account_email', 'LIKE',"%{$search}%")
                            ->orWhere('first_name', 'LIKE',"%{$search}%")
                            ->orWhere('last_name', 'LIKE',"%{$search}%")
                            ->orWhere('phone', 'LIKE',"%{$search}%")
                            ->orWhere('source', 'LIKE',"%{$search}%");
        }

        // Searcy by column
        if (empty($request->input('columns'))) {
            // $posts = Product::offset($start)
            //              ->limit($limit)
            //              ->orderBy($order,$dir)
            //              ->get();
            
        } else {
            $whereSql = [];

            // Text filter
            if(!empty($request->input('columns.1.search.value'))) {
                $searchPheramorID = $request->input('columns.1.search.value');
                array_push($whereSql, ['pheramor_id', 'LIKE', "%{$searchPheramorID}%"]);
            }
            if(!empty($request->input('columns.2.search.value'))) {
                $searchSalesEmail = $request->input('columns.2.search.value');
                array_push($whereSql, ['sales_email', 'LIKE', "%{$searchSalesEmail}%"]);
            }
            if(!empty($request->input('columns.3.search.value'))) {
                $searchAccountEmail = $request->input('columns.3.search.value');
                array_push($whereSql, ['account_email', 'LIKE', "%{$searchAccountEmail}%"]);
            }
            if(!empty($request->input('columns.4.search.value'))) {
                $searchFirstName = $request->input('columns.4.search.value');
                array_push($whereSql, ['first_name', 'LIKE', "%{$searchFirstName}%"]);
            }
            if(!empty($request->input('columns.5.search.value'))) {
                $searchLastName = $request->input('columns.5.search.value');
                array_push($whereSql, ['last_name', 'LIKE', "%{$searchLastName}%"]);
            }
            if(!empty($request->input('columns.6.search.value'))) {
                $searchPhone = $request->input('columns.6.search.value');
                array_push($whereSql, ['phone', 'LIKE', "%{$searchPhone}%"]);
            }
            if(!empty($request->input('columns.7.search.value'))) {
                $searchSource = $request->input('columns.7.search.value');
                array_push($whereSql, ['source', 'LIKE', "%{$searchSource}%"]);
            }

            $whereResult = Product::where($whereSql);
            if(!empty($posts)) {
                $whereResult = $posts;
            }

            // Date filter
            if(!empty($request->input('columns.8.search.value'))) {
                $searchCreatedDate = $request->input('columns.8.search.value');
                $createdDates = explode("|", $searchCreatedDate);
                $from = date($createdDates[0]);
                $to = date($createdDates[1]);
                $whereResult->whereBetween('created_at', [$from, $to]);
            }
            if(!empty($request->input('columns.9.search.value'))) {
                $searchSalesDate = $request->input('columns.9.search.value');
                $salesDates = explode("|", $searchSalesDate);
                $from = date($salesDates[0]);
                $to = date($salesDates[1]);
                $whereResult->whereBetween('sales_date', [$from, $to]);
            }
            if(!empty($request->input('columns.10.search.value'))) {
                $searchShipDate = $request->input('columns.10.search.value');
                $shipDates = explode("|", $searchShipDate);
                $from = date($shipDates[0]);
                $to = date($shipDates[1]);
                $whereResult->whereBetween('ship_date', [$from, $to]);
            }
            if(!empty($request->input('columns.11.search.value'))) {
                $searchAccountDate = $request->input('columns.11.search.value');
                $accountDates = explode("|", $searchAccountDate);
                $from = date($accountDates[0]);
                $to = date($accountDates[1]);
                $whereResult->whereBetween('account_connected_date', [$from, $to]);
            }
            if(!empty($request->input('columns.12.search.value'))) {
                $searchSwabDate = $request->input('columns.12.search.value');
                $swabDates = explode("|", $searchSwabDate);
                $from = date($swabDates[0]);
                $to = date($swabDates[1]);
                $whereResult->whereBetween('swab_returned_date', [$from, $to]);
            }
            if(!empty($request->input('columns.13.search.value'))) {
                $searchShipToLabDate = $request->input('columns.13.search.value');
                $shipToLabDates = explode("|", $searchShipToLabDate);
                $from = date($shipToLabDates[0]);
                $to = date($shipToLabDates[1]);
                $whereResult->whereBetween('ship_to_lab_date', [$from, $to]);
            }
            if(!empty($request->input('columns.14.search.value'))) {
                $searchLabReceivedDate = $request->input('columns.14.search.value');
                $labReceivedDates = explode("|", $searchLabReceivedDate);
                $from = date($labReceivedDates[0]);
                $to = date($labReceivedDates[1]);
                $whereResult->whereBetween('lab_received_date', [$from, $to]);
            }
            if(!empty($request->input('columns.15.search.value'))) {
                $searchSequencedDate = $request->input('columns.15.search.value');
                $sequencedDates = explode("|", $searchSequencedDate);
                $from = date($sequencedDates[0]);
                $to = date($sequencedDates[1]);
                $whereResult->whereBetween('sequenced_date', [$from, $to]);
            }
            if(!empty($request->input('columns.16.search.value'))) {
                $searchUploadedDate = $request->input('columns.16.search.value');
                $uploadedDates = explode("|", $searchUploadedDate);
                $from = date($uploadedDates[0]);
                $to = date($uploadedDates[1]);
                $whereResult->whereBetween('uploaded_to_server_date', [$from, $to]);
            }
            if(!empty($request->input('columns.17.search.value'))) {
                $searchConsentDate = $request->input('columns.17.search.value');
                $consentDates = explode("|", $searchConsentDate);
                $from = date($consentDates[0]);
                $to = date($consentDates[1]);
                $whereResult->whereBetween('bone_marrow_consent_date', [$from, $to]);
            }
            if(!empty($request->input('columns.18.search.value'))) {
                $searchSharedDate = $request->input('columns.18.search.value');
                $sharedDates = explode("|", $searchSharedDate);
                $from = date($sharedDates[0]);
                $to = date($sharedDates[1]);
                $whereResult->whereBetween('bone_marrow_shared_date', [$from, $to]);
            }

            $posts =  $whereResult
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Product::where($whereSql)
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = '<input type="checkbox" class="minimal" data-id="' . $post->id . '">';
                $nestedData['pheramor_id'] = $post->pheramor_id;
                $nestedData['sales_email'] = $post->sales_email;
                $nestedData['account_email'] = $post->account_email;
                $nestedData['first_name'] = $post->first_name;
                $nestedData['last_name'] = $post->last_name;
                $nestedData['phone'] = $post->phone;
                $nestedData['source'] = $post->source;
                $nestedData['created_at'] = date('Y-m-j',strtotime($post->created_at));
                $nestedData['sales_date'] = $post->sales_date;
                $nestedData['ship_date'] = $post->ship_date;
                $nestedData['account_connected_date'] = $post->account_connected_date;
                $nestedData['swab_returned_date'] = $post->swab_returned_date;
                $nestedData['ship_to_lab_date'] = $post->ship_to_lab_date;
                $nestedData['lab_received_date'] = $post->lab_received_date;
                $nestedData['sequenced_date'] = $post->sequenced_date;
                $nestedData['uploaded_to_server_date'] = $post->uploaded_to_server_date;
                $nestedData['bone_marrow_consent_date'] = $post->bone_marrow_consent_date;
                $nestedData['bone_marrow_shared_date'] = $post->bone_marrow_shared_date;
                $nestedData['note'] = $post->note;
                if (Auth::user()->hasRole('super admin')) {
                    $nestedData['actions'] = '<button class="btn btn-xs btn-success update-product" data-id="' . $post->id . '">update</button>
                                          <button class="btn btn-xs btn-info update-note" data-id="' . $post->id . '">note</button>
                                          <button class="btn btn-xs btn-danger delete-product" data-id="' . $post->id . '"><i class="fa fa-trash"></i></button>';
                } else {
                    $nestedData['actions'] = '<button class="btn btn-xs btn-success update-product" data-id="' . $post->id . '">update</button>
                                          <button class="btn btn-xs btn-info update-note" data-id="' . $post->id . '">note</button>';
                }
                
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
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
        $this->product->pheramor_id = $request->get('pheramor_id');
        $this->product->sales_email = $request->get('sales_email');
        $this->product->first_name = $request->get('first_name');
        $this->product->last_name = $request->get('last_name');
        $this->product->note = $request->get('note');
        $this->product->source = Auth::user()->roles[0]->name;

        $this->product->save();

        return response()->json(['status' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        return response()->json([
            'status' => true,
            'data'   => $product
        ], 200);
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
        $product = $this->product::find($id);

        $product->sales_date = $request->get('sales_date');
        $product->ship_date = $request->get('ship_date');
        $product->account_connected_date = $request->get('account_connected_date');
        $product->swab_returned_date = $request->get('swab_returned_date');
        $product->ship_to_lab_date = $request->get('ship_to_lab_date');
        $product->lab_received_date = $request->get('lab_received_date');
        $product->sequenced_date = $request->get('sequenced_date');
        $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
        $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
        $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
        $product->first_name= $request->get('first_name');
        $product->last_name= $request->get('last_name');
        $product->sales_email= $request->get('sales_email');
        $product->account_email = $request->get('account_email');
        $product->phone = $request->get('phone');
        $product->note = $request->get('note');

        $product->save();

        return response()->json(['status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);

        $this->product->destroy($id);

        return response()->json(['status' => true], 200);
    }

    /**
     * Update the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $ids = $request->get('ids');

        if (is_array($ids)) {
            foreach ($ids as $key => $id) {
                $product = $this->product::find($id);

                if ($request->get('sales_date') != '') {
                    $product->sales_date = $request->get('sales_date');
                    $this->sendMail($product, 'sales_update');
                }

                if ($request->get('ship_date') != '') {
                    $product->ship_date = $request->get('ship_date');
                    $this->sendMail($product, 'ship_update');
                    $this->prepareReminderEmail($product->id);
                }
                
                if ($request->get('account_connected_date') != '') {
                   $product->account_connected_date = $request->get('account_connected_date');
                   $this->sendMail($product, 'account_update');
                }

                if ($request->get('swab_returned_date') != '') {
                   $product->swab_returned_date = $request->get('swab_returned_date');
                   $this->sendMail($product, 'swab_update');
                }

                if ($request->get('ship_to_lab_date') != '') {
                   $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                }

                if ($request->get('lab_received_date') != '') {
                   $product->lab_received_date = $request->get('lab_received_date');
                }

                if ($request->get('sequenced_date') != '') {
                   $product->sequenced_date = $request->get('sequenced_date');
                   $this->sendMail($product, 'sequenced_update');
                }

                if ($request->get('uploaded_to_server_date') != '') {
                   $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
                }

                if ($request->get('bone_marrow_consent_date') != '') {
                   $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
                }

                if ($request->get('bone_marrow_shared_date') != '') {
                   $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
                }

                $product->save();
            }
        } else {
            $product = $this->product->find($ids);

            if ($product->sales_date != $request->get('sales_date')) {
                $product->sales_date = $request->get('sales_date');
                $this->sendMail($product, 'sales_update');
            }

            if ($product->ship_date != $request->get('ship_date')) {
                $product->ship_date = $request->get('ship_date');
                $this->sendMail($product, 'ship_update');
                $this->prepareReminderEmail($product->id);
            }

            if ($product->account_connected_date != $request->get('account_connected_date')) {
                $product->account_connected_date = $request->get('account_connected_date');
                $this->sendMail($product, 'account_update');
            }

            if ($product->swab_returned_date != $request->get('swab_returned_date')) {
                $product->swab_returned_date = $request->get('swab_returned_date');
                $this->sendMail($product, 'swab_update');
            }

            if ($product->ship_to_lab_date != $request->get('ship_to_lab_date')) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
            }

            if ($product->lab_received_date != $request->get('lab_received_date')) {
                $product->lab_received_date = $request->get('lab_received_date');
            }

            if ($product->sequenced_date != $request->get('sequenced_date')) {
                $product->sequenced_date = $request->get('sequenced_date');
                $this->sendMail($product, 'sequenced_update');
            }

            if ($product->uploaded_to_server_date != $request->get('uploaded_to_server_date')) {
                $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
            }

            if ($product->bone_marrow_consent_date != $request->get('bone_marrow_consent_date')) {
                $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
            }

            if ($product->bone_marrow_shared_date != $request->get('bone_marrow_shared_date')) {
                $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
            }

            $product->first_name= $request->get('first_name');
            $product->last_name= $request->get('last_name');
            $product->sales_email= $request->get('sales_email');
            $product->account_email = $request->get('account_email');
            $product->phone = $request->get('phone');

            $product->save();

        }
        return response()->json(['status' => true], 200);
    }

    /**
     * Update & Create the specified resources in storage from csv file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateByCSV(Request $request)
    {
        $data = $request->all();
        $customers = $data['customers'];
        $dates = $data['dates'];
        $create_data = array();

        foreach ($customers as $key => $value) {

            if ($value['id'] != '') { // Update customer

                $product = array();

                if($value['sales_email'] != '') {
                    $product['sales_email'] = $value['sales_email'];
                }

                if ($dates['sales_date']  != '') {
                    $product['sales_date'] = date('Y-m-d', strtotime($dates['sales_date']));
                }

                if ($dates['ship_date']  != '') {
                    $product['ship_date'] = date('Y-m-d', strtotime($dates['ship_date']));
                }

                if ($dates['account_connected_date']  != '') {
                    $product['account_connected_date'] = date('Y-m-d', strtotime($dates['account_connected_date']));
                }

                if ($dates['swab_returned_date']  != '') {
                    $product['swab_returned_date'] = date('Y-m-d', strtotime($dates['swab_returned_date']));
                }

                if ($dates['ship_to_lab_date']  != '') {
                    $product['ship_to_lab_date'] = date('Y-m-d', strtotime($dates['ship_to_lab_date']));
                }

                if ($dates['lab_received_date']  != '') {
                    $product['lab_received_date'] = date('Y-m-d', strtotime($dates['lab_received_date']));
                }

                if ($dates['sequenced_date']  != '') {
                    $product['sequenced_date'] = date('Y-m-d', strtotime($dates['sequenced_date']));
                }

                if ($dates['uploaded_to_server_date']  != '') {
                    $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($dates['uploaded_to_server_date']));
                }

                if ($dates['bone_marrow_consent_date']  != '') {
                    $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($dates['bone_marrow_consent_date']));
                }

                if ($dates['bone_marrow_shared_date']  != '') {
                    $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($dates['bone_marrow_shared_date']));
                }
                
                Product::where('id', $value['id'])->update($product);

            } else { // Create customer

                $product = array();

                $product['pheramor_id'] = $value['pheramor_id'];
                if($value['sales_email'] != '') {
                    $product['sales_email'] = $value['sales_email'];
                }
                $product['source'] = Auth::user()->roles[0]->name;

                if ($dates['sales_date']  != '') {
                    $product['sales_date'] = date('Y-m-d', strtotime($dates['sales_date']));
                }

                if ($dates['ship_date']  != '') {
                    $product['ship_date'] = date('Y-m-d', strtotime($dates['ship_date']));
                }

                if ($dates['account_connected_date']  != '') {
                    $product['account_connected_date'] = date('Y-m-d', strtotime($dates['account_connected_date']));
                }

                if ($dates['swab_returned_date']  != '') {
                    $product['swab_returned_date'] = date('Y-m-d', strtotime($dates['swab_returned_date']));
                }

                if ($dates['ship_to_lab_date']  != '') {
                    $product['ship_to_lab_date'] = date('Y-m-d', strtotime($dates['ship_to_lab_date']));
                }

                if ($dates['lab_received_date']  != '') {
                    $product['lab_received_date'] = date('Y-m-d', strtotime($dates['lab_received_date']));
                }

                if ($dates['sequenced_date']  != '') {
                    $product['sequenced_date'] = date('Y-m-d', strtotime($dates['sequenced_date']));
                }

                if ($dates['uploaded_to_server_date']  != '') {
                    $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($dates['uploaded_to_server_date']));
                }

                if ($dates['bone_marrow_consent_date']  != '') {
                    $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($dates['bone_marrow_consent_date']));
                }

                if ($dates['bone_marrow_shared_date']  != '') {
                    $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($dates['bone_marrow_shared_date']));
                }

                $product['created_at'] = \Carbon\Carbon::now();
                $product['updated_at'] = \Carbon\Carbon::now();

                array_push($create_data, $product);
            }
        }

        Product::insert($create_data);

        return response()->json(['status' => true], 200);
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
                $log_success_text = $log_prefix . ' successfuly sent to ' . $product->sales_email . ' by ' . Auth::guard('web')->user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
                $log_fail_text = $log_prefix . ' can not send to ' . $product->sales_email . ' by ' . Auth::guard('web')->user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
            } else {
                Mail::to($to)
                    ->cc($cc)
                    ->queue(new StatusUpdated($data, $type));
                $log_success_text = $log_prefix . ' successfuly sent to ' . $product->sales_email . ', ' . $product->account_email . ' by ' . Auth::guard('web')->user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
                $log_fail_text = $log_prefix . ' can not send to ' . $product->sales_email . ', ' . $product->account_email . ' by ' . Auth::guard('web')->user()->name . ' at ' . date('Y-m-d h:m:s') . '.';
            }

            // Log status whether email sent successfuly or not.
            if( count(Mail::failures()) > 0 ) {
                activity('mail')
                    ->causedBy(Auth::guard('web')->user()->id)
                    ->log( $log_fail_text );

            } else {
                activity('mail')
                    ->causedBy(Auth::guard('web')->user()->id)
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
}
