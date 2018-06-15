<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Tag;
use App\Config;
use Spatie\Activitylog\Models\Activity;

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

        if(empty($request->input('search.value')))
        {            
            $posts = Product::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
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
                            ->orWhere('source', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Product::where('id','LIKE',"%{$search}%")
                             ->orWhere('pheramor_id', 'LIKE',"%{$search}%")
                             ->orWhere('sales_email', 'LIKE',"%{$search}%")
                             ->orWhere('account_email', 'LIKE',"%{$search}%")
                             ->orWhere('first_name', 'LIKE',"%{$search}%")
                             ->orWhere('last_name', 'LIKE',"%{$search}%")
                             ->orWhere('phone', 'LIKE',"%{$search}%")
                             ->orWhere('source', 'LIKE',"%{$search}%")
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
                $nestedData['actions'] = '<button class="btn btn-xs btn-success update-product" data-id="' . $post->id . '">update</button>
                                          <button class="btn btn-xs btn-info update-note" data-id="' . $post->id . '">note</button>
                                          <button class="btn btn-xs btn-danger delete-product" data-id="' . $post->id . '"><i class="fa fa-trash"></i></button>';
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

        $this->addContact($request->get('sales_email'), $request->get('pheramor_id'), array());

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

        $result = $this->deleteContact($product->sales_email);
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

                $tags = array();

                if ($request->get('sales_date') != '') {
                   $product->sales_date = $request->get('sales_date');
                   array_push($tags, $this->getTagValue('sales_date'));
                }

                if ($request->get('ship_date') != '') {
                   $product->ship_date = $request->get('ship_date');
                   array_push($tags, $this->getTagValue('ship_date'));
                }
                
                if ($request->get('account_connected_date') != '') {
                   $product->account_connected_date = $request->get('account_connected_date');
                   array_push($tags, $this->getTagValue('account_connected_date'));
                }

                if ($request->get('swab_returned_date') != '') {
                   $product->swab_returned_date = $request->get('swab_returned_date');
                   array_push($tags, $this->getTagValue('swab_returned_date'));
                }

                if ($request->get('ship_to_lab_date') != '') {
                   $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                   array_push($tags, $this->getTagValue('ship_to_lab_date'));
                }

                if ($request->get('lab_received_date') != '') {
                   $product->lab_received_date = $request->get('lab_received_date');
                   array_push($tags, $this->getTagValue('lab_received_date'));
                }

                if ($request->get('sequenced_date') != '') {
                   $product->sequenced_date = $request->get('sequenced_date');
                   array_push($tags, $this->getTagValue('sequenced_date'));
                }

                if ($request->get('uploaded_to_server_date') != '') {
                   $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
                   array_push($tags, $this->getTagValue('uploaded_to_server_date'));
                }

                if ($request->get('bone_marrow_consent_date') != '') {
                   $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
                   array_push($tags, $this->getTagValue('bone_marrow_consent_date'));
                }

                if ($request->get('bone_marrow_shared_date') != '') {
                   $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
                   array_push($tags, $this->getTagValue('bone_marrow_shared_date'));
                }

                $this->addTag($product->sales_email, $tags, $product->pheramor_id);

                $product->save();
            }
        } else {
            $product = $this->product->find($ids);

            $tags = array();

            if ($product->sales_date != $request->get('sales_date')) {
                $product->sales_date = $request->get('sales_date');
                array_push($tags, $this->getTagValue('sales_date'));
            }

            if ($product->ship_date != $request->get('ship_date')) {
                $product->ship_date = $request->get('ship_date');
                array_push($tags, $this->getTagValue('ship_date'));
            }

            if ($product->account_connected_date != $request->get('account_connected_date')) {
                $product->account_connected_date = $request->get('account_connected_date');
                array_push($tags, $this->getTagValue('account_connected_date'));
            }

            if ($product->swab_returned_date != $request->get('swab_returned_date')) {
                $product->swab_returned_date = $request->get('swab_returned_date');
                array_push($tags, $this->getTagValue('swab_returned_date'));
            }

            if ($product->ship_to_lab_date != $request->get('ship_to_lab_date')) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                array_push($tags, $this->getTagValue('ship_to_lab_date'));
            }

            if ($product->lab_received_date != $request->get('lab_received_date')) {
                $product->lab_received_date = $request->get('lab_received_date');
                array_push($tags, $this->getTagValue('lab_received_date'));
            }

            if ($product->sequenced_date != $request->get('sequenced_date')) {
                $product->sequenced_date = $request->get('sequenced_date');
                array_push($tags, $this->getTagValue('sequenced_date'));
            }

            if ($product->uploaded_to_server_date != $request->get('uploaded_to_server_date')) {
                $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
                array_push($tags, $this->getTagValue('uploaded_to_server_date'));
            }

            if ($product->bone_marrow_consent_date != $request->get('bone_marrow_consent_date')) {
                $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
                array_push($tags, $this->getTagValue('bone_marrow_consent_date'));
            }

            if ($product->bone_marrow_shared_date != $request->get('bone_marrow_shared_date')) {
                $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
                array_push($tags, $this->getTagValue('bone_marrow_shared_date'));
            }

            $product->first_name= $request->get('first_name');
            $product->last_name= $request->get('last_name');
            $product->sales_email= $request->get('sales_email');
            $product->account_email = $request->get('account_email');
            $product->phone = $request->get('phone');

            $product->save();

            $this->addTag($product->sales_email, $tags, $product->pheramor_id);
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

                $product['sales_email'] = $value['sales_email'];

                $tags = array();

                if ($dates['sales_date']  != '') {
                    $product['sales_date'] = date('Y-m-d', strtotime($dates['sales_date']));
                    array_push($tags, $this->getTagValue('sales_date'));
                }

                if ($dates['ship_date']  != '') {
                    $product['ship_date'] = date('Y-m-d', strtotime($dates['ship_date']));
                    array_push($tags, $this->getTagValue('ship_date'));
                }

                if ($dates['account_connected_date']  != '') {
                    $product['account_connected_date'] = date('Y-m-d', strtotime($dates['account_connected_date']));
                    array_push($tags, $this->getTagValue('account_connected_date'));
                }

                if ($dates['swab_returned_date']  != '') {
                    $product['swab_returned_date'] = date('Y-m-d', strtotime($dates['swab_returned_date']));
                    array_push($tags, $this->getTagValue('swab_returned_date'));
                }

                if ($dates['ship_to_lab_date']  != '') {
                    $product['ship_to_lab_date'] = date('Y-m-d', strtotime($dates['ship_to_lab_date']));
                    array_push($tags, $this->getTagValue('ship_to_lab_date'));
                }

                if ($dates['lab_received_date']  != '') {
                    $product['lab_received_date'] = date('Y-m-d', strtotime($dates['lab_received_date']));
                    array_push($tags, $this->getTagValue('lab_received_date'));
                }

                if ($dates['sequenced_date']  != '') {
                    $product['sequenced_date'] = date('Y-m-d', strtotime($dates['sequenced_date']));
                    array_push($tags, $this->getTagValue('sequenced_date'));
                }

                if ($dates['uploaded_to_server_date']  != '') {
                    $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($dates['uploaded_to_server_date']));
                    array_push($tags, $this->getTagValue('uploaded_to_server_date'));
                }

                if ($dates['bone_marrow_consent_date']  != '') {
                    $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($dates['bone_marrow_consent_date']));
                    array_push($tags, $this->getTagValue('bone_marrow_consent_date'));
                }

                if ($dates['bone_marrow_shared_date']  != '') {
                    $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($dates['bone_marrow_shared_date']));
                    array_push($tags, $this->getTagValue('bone_marrow_shared_date'));
                }
                
                Product::where('id', $value['id'])->update($product);

                $this->addTag($this->product->find($value['id'])->sales_email, $tags, $this->product->find($value['id'])->pheramor_id);
            } else { // Create customer

                $product = array();

                $tags = array();

                $product['pheramor_id'] = $value['pheramor_id'];
                $product['sales_email'] = $value['sales_email'];
                $product['source'] = Auth::user()->roles[0]->name;

                if ($dates['sales_date']  != '') {
                    $product['sales_date'] = date('Y-m-d', strtotime($dates['sales_date']));
                    array_push($tags, $this->getTagValue('sales_date'));
                }

                if ($dates['ship_date']  != '') {
                    $product['ship_date'] = date('Y-m-d', strtotime($dates['ship_date']));
                    array_push($tags, $this->getTagValue('ship_date'));
                }

                if ($dates['account_connected_date']  != '') {
                    $product['account_connected_date'] = date('Y-m-d', strtotime($dates['account_connected_date']));
                    array_push($tags, $this->getTagValue('account_connected_date'));
                }

                if ($dates['swab_returned_date']  != '') {
                    $product['swab_returned_date'] = date('Y-m-d', strtotime($dates['swab_returned_date']));
                    array_push($tags, $this->getTagValue('swab_returned_date'));
                }

                if ($dates['ship_to_lab_date']  != '') {
                    $product['ship_to_lab_date'] = date('Y-m-d', strtotime($dates['ship_to_lab_date']));
                    array_push($tags, $this->getTagValue('ship_to_lab_date'));
                }

                if ($dates['lab_received_date']  != '') {
                    $product['lab_received_date'] = date('Y-m-d', strtotime($dates['lab_received_date']));
                    array_push($tags, $this->getTagValue('lab_received_date'));
                }

                if ($dates['sequenced_date']  != '') {
                    $product['sequenced_date'] = date('Y-m-d', strtotime($dates['sequenced_date']));
                    array_push($tags, $this->getTagValue('sequenced_date'));
                }

                if ($dates['uploaded_to_server_date']  != '') {
                    $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($dates['uploaded_to_server_date']));
                    array_push($tags, $this->getTagValue('uploaded_to_server_date'));
                }

                if ($dates['bone_marrow_consent_date']  != '') {
                    $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($dates['bone_marrow_consent_date']));
                    array_push($tags, $this->getTagValue('bone_marrow_consent_date'));
                }

                if ($dates['bone_marrow_shared_date']  != '') {
                    $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($dates['bone_marrow_shared_date']));
                    array_push($tags, $this->getTagValue('bone_marrow_shared_date'));
                }

                $product['created_at'] = \Carbon\Carbon::now();
                $product['updated_at'] = \Carbon\Carbon::now();

                array_push($create_data, $product);

                $existContact = $this->getContact($value['sales_email']);
                if(empty((array)$existContact)) {
                    $this->addTag($value['sales_email'], $tags, $value['pheramor_id']);
                } else {
                    $this->addContact($value['pheramor_id'], $value['sales_email'], $tags);
                }
            }
        }

        Product::insert($create_data);

        return response()->json(['status' => true], 200);
    }

    // Add a tag to AgileCRM contact
    protected function addTag($email, $tags, $pheramor_id) {

        if (count($tags) > 0) {
            $str_tags = '["' . implode('","', $tags) . '"]';

            $fields = array(
                'email' => urlencode($email),
                'tags' => urlencode($str_tags)
            );

            $fields_string = '';

            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }

            $status = $this->curl_wrap("contacts/email/tags/add", rtrim($fields_string, '&'), "POST", "application/x-www-form-urlencoded");

            // Register log activity
            $auth_name = Auth::guard('web')->user()->name;
            $auth_id = Auth::guard('web')->user()->id;
            $log_text = '';
            $str = implode(', ', $tags);
            if($status == true) {
                $log_text = $auth_name . ' added new tag (' . $str . ') to ' . $pheramor_id . ' on AgileCRM at ' . date('Y-m-d h:m:s') . '.';
            } else {
                $log_text = $auth_name . ' failed to add new tag (' . $str . ') to ' . $pheramor_id . ' on AgileCRM at ' . date('Y-m-d h:m:s') . '.';
            }
            
            activity('agile')
                ->causedBy($auth_id)
                ->log($log_text);
        }
    }

    // Add new contact to AgileCRM
    protected function addContact($email, $pheramor_id, $tags) {
        $contact_json = array(
          "tags"=>$tags,
          "properties"=>array(
            array(
              "name"=>"email",
              "value"=>$email,
              "type"=>"SYSTEM"
            ),
            array(
                "name"=>"Pheramor ID",
                "value"=>$pheramor_id,      // This is epoch time in seconds.
                "type"=>"CUSTOM"
            )
            
          )
        );

        $contact_json = json_encode($contact_json);
        $status = $this->curl_wrap("contacts", $contact_json, "POST", "application/json");

        // Register log activity
        $auth_name = Auth::guard('web')->user()->name;
        $auth_id = Auth::guard('web')->user()->id;
        $log_text = '';
        $str = implode(', ', $tags);
        if($status == true) {
            $log_text = $auth_name . ' added new contact (' . $pheramor_id . ', ' . $email . ') to AgileCRM at ' . date('Y-m-d h:m:s') . '.';
        } else {
            $log_text = $auth_name . ' failed to add new contact (' . $pheramor_id . ', ' . $email . ') to AgileCRM at ' . date('Y-m-d h:m:s') . '.';
        }
        
        activity('agile')
            ->causedBy($auth_id)
            ->log($log_text);
    }

    // Delete contact from AgileCRM
    public function deleteContact($email) {
        $contact = $this->getContact($email);
        if(!empty(json_decode($contact))) {
            $id = substr($contact, 6, 16);
            $url = "contacts/" . $id;
            $this->curl_wrap($url, null, "DELETE", "application/json");
            return true;
        }
        return false;
    }

    // Get contact by email
    protected function getContact($email) {
        $url = "contacts/search/email/" . $email;
        $result = $this->curl_wrap($url, null, "GET", "application/json");
        return $result;
    }

    // Curl request for AgileCRM.
    protected function curl_wrap($entity, $data, $method, $content_type) {
        if ($content_type == NULL) {
            $content_type = "application/json";
        }
        
        $agile_domain = Config::all()->first()->agile_domain;
        $agile_user_email = Config::all()->first()->agile_email;
        $agile_api_key = Config::all()->first()->agile_key;

        $agile_url = "https://" . $agile_domain . ".agilecrm.com/dev/api/" . $entity;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
        switch ($method) {
            case "POST":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                $url = $agile_url;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type : $content_type;", 'Accept : application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $agile_user_email . ':' . $agile_api_key);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    // Get tag value
    protected function getTagValue($key) {
        return Tag::where('selector', $key)->first()->value;
    }
}
