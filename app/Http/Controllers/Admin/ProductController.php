<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
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
        $products = $this->product->get();
        return view('admin.products', [
            'products' => $products
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
        $this->product->pheramor_id = $request->get('pheramor_id');
        $this->product->sales_email = $request->get('sales_email');
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
        //
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
                   $this->addTag($product->sales_email, $this->getTagValue('sales_date'), $product->pheramor_id);
                }

                if ($request->get('ship_date') != '') {
                   $product->ship_date = $request->get('ship_date');
                   $this->addTag($product->sales_email, $this->getTagValue('ship_date'), $product->pheramor_id);
                }
                
                if ($request->get('account_connected_date') != '') {
                   $product->account_connected_date = $request->get('account_connected_date');
                   $this->addTag($product->sales_email, $this->getTagValue('account_connected_date'), $product->pheramor_id);
                }

                if ($request->get('swab_returned_date') != '') {
                   $product->swab_returned_date = $request->get('swab_returned_date');
                   $this->addTag($product->sales_email, $this->getTagValue('swab_returned_date'), $product->pheramor_id);
                }

                if ($request->get('ship_to_lab_date') != '') {
                   $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                   $this->addTag($product->sales_email, $this->getTagValue('ship_to_lab_date'), $product->pheramor_id);
                }

                if ($request->get('lab_received_date') != '') {
                   $product->lab_received_date = $request->get('lab_received_date');
                   $this->addTag($product->sales_email, $this->getTagValue('lab_received_date'), $product->pheramor_id);
                }

                if ($request->get('sequenced_date') != '') {
                   $product->sequenced_date = $request->get('sequenced_date');
                   $this->addTag($product->sales_email, $this->getTagValue('sequenced_date'), $product->pheramor_id);
                }

                if ($request->get('uploaded_to_server_date') != '') {
                   $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
                   $this->addTag($product->sales_email, $this->getTagValue('uploaded_to_server_date'), $product->pheramor_id);
                }

                if ($request->get('bone_marrow_consent_date') != '') {
                   $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
                   $this->addTag($product->sales_email, $this->getTagValue('bone_marrow_consent_date'), $product->pheramor_id);
                }

                if ($request->get('bone_marrow_shared_date') != '') {
                   $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
                   $this->addTag($product->sales_email, $this->getTagValue('bone_marrow_shared_date'), $product->pheramor_id);
                }

                $product->save();
            }
        } else {
            $product = $this->product->find($ids);

            if ($product->sales_date != $request->get('sales_date')) {
                $product->sales_date = $request->get('sales_date');
                $this->addTag($product->sales_email, $this->getTagValue('sales_date'), $product->pheramor_id);
            }

            if ($product->ship_date != $request->get('ship_date')) {
                $product->ship_date = $request->get('ship_date');
                $this->addTag($product->sales_email, $this->getTagValue('ship_date'), $product->pheramor_id);
            }

            if ($product->account_connected_date != $request->get('account_connected_date')) {
                $product->account_connected_date = $request->get('account_connected_date');
                $this->addTag($product->sales_email, $this->getTagValue('account_connected_date'), $product->pheramor_id);
            }

            if ($product->swab_returned_date != $request->get('swab_returned_date')) {
                $product->swab_returned_date = $request->get('swab_returned_date');
                $this->addTag($product->sales_email, $this->getTagValue('swab_returned_date'), $product->pheramor_id);
            }

            if ($product->ship_to_lab_date != $request->get('ship_to_lab_date')) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                $this->addTag($product->sales_email, $this->getTagValue('ship_to_lab_date'), $product->pheramor_id);
            }

            if ($product->lab_received_date != $request->get('lab_received_date')) {
                $product->lab_received_date = $request->get('lab_received_date');
                $this->addTag($product->sales_email, $this->getTagValue('lab_received_date'), $product->pheramor_id);
            }

            if ($product->sequenced_date != $request->get('sequenced_date')) {
                $product->sequenced_date = $request->get('sequenced_date');
                $this->addTag($product->sales_email, $this->getTagValue('sequenced_date'), $product->pheramor_id);
            }

            if ($product->uploaded_to_server_date != $request->get('uploaded_to_server_date')) {
                $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
                $this->addTag($product->sales_email, $this->getTagValue('uploaded_to_server_date'), $product->pheramor_id);
            }

            if ($product->bone_marrow_consent_date != $request->get('bone_marrow_consent_date')) {
                $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
                $this->addTag($product->sales_email, $this->getTagValue('bone_marrow_consent_date'), $product->pheramor_id);
            }

            if ($product->bone_marrow_shared_date != $request->get('bone_marrow_shared_date')) {
                $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
                $this->addTag($product->sales_email, $this->getTagValue('bone_marrow_shared_date'), $product->pheramor_id);
            }

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

                $product['sales_email'] = $value['sales_email'];

                if ($dates['sales_date']  != '') {
                    $product['sales_date'] = date('Y-m-d', strtotime($dates['sales_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('sales_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['ship_date']  != '') {
                    $product['ship_date'] = date('Y-m-d', strtotime($dates['ship_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('ship_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['account_connected_date']  != '') {
                    $product['account_connected_date'] = date('Y-m-d', strtotime($dates['account_connected_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('account_connected_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['swab_returned_date']  != '') {
                    $product['swab_returned_date'] = date('Y-m-d', strtotime($dates['swab_returned_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('swab_returned_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['ship_to_lab_date']  != '') {
                    $product['ship_to_lab_date'] = date('Y-m-d', strtotime($dates['ship_to_lab_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('ship_to_lab_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['lab_received_date']  != '') {
                    $product['lab_received_date'] = date('Y-m-d', strtotime($dates['lab_received_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('lab_received_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['sequenced_date']  != '') {
                    $product['sequenced_date'] = date('Y-m-d', strtotime($dates['sequenced_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('sequenced_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['uploaded_to_server_date']  != '') {
                    $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($dates['uploaded_to_server_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('uploaded_to_server_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['bone_marrow_consent_date']  != '') {
                    $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($dates['bone_marrow_consent_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('bone_marrow_consent_date'), $this->product->find($value['id'])->pheramor_id);
                }

                if ($dates['bone_marrow_shared_date']  != '') {
                    $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($dates['bone_marrow_shared_date']));
                    $this->addTag($this->product->find($value['id'])->sales_email, $this->getTagValue('bone_marrow_shared_date'), $this->product->find($value['id'])->pheramor_id);
                }
                
                Product::where('id', $value['id'])->update($product);
            } else { // Create customer

                $product = array();

                $product['pheramor_id'] = $value['pheramor_id'];
                $product['sales_email'] = $value['sales_email'];
                $product['source'] = Auth::user()->roles[0]->name;

                if ($dates['sales_date']  != '') {
                    $product['sales_date'] = date('Y-m-d', strtotime($dates['sales_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('sales_date'), $value['pheramor_id']);
                }

                if ($dates['ship_date']  != '') {
                    $product['ship_date'] = date('Y-m-d', strtotime($dates['ship_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('ship_date'), $value['pheramor_id']);
                }

                if ($dates['account_connected_date']  != '') {
                    $product['account_connected_date'] = date('Y-m-d', strtotime($dates['account_connected_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('account_connected_date'), $value['pheramor_id']);
                }

                if ($dates['swab_returned_date']  != '') {
                    $product['swab_returned_date'] = date('Y-m-d', strtotime($dates['swab_returned_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('swab_returned_date'), $value['pheramor_id']);
                }

                if ($dates['ship_to_lab_date']  != '') {
                    $product['ship_to_lab_date'] = date('Y-m-d', strtotime($dates['ship_to_lab_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('ship_to_lab_date'), $value['pheramor_id']);
                }

                if ($dates['lab_received_date']  != '') {
                    $product['lab_received_date'] = date('Y-m-d', strtotime($dates['lab_received_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('lab_received_date'), $value['pheramor_id']);
                }

                if ($dates['sequenced_date']  != '') {
                    $product['sequenced_date'] = date('Y-m-d', strtotime($dates['sequenced_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('sequenced_date'), $value['pheramor_id']);
                }

                if ($dates['uploaded_to_server_date']  != '') {
                    $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($dates['uploaded_to_server_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('uploaded_to_server_date'), $value['pheramor_id']);
                }

                if ($dates['bone_marrow_consent_date']  != '') {
                    $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($dates['bone_marrow_consent_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('bone_marrow_consent_date'), $value['pheramor_id']);
                }

                if ($dates['bone_marrow_shared_date']  != '') {
                    $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($dates['bone_marrow_shared_date']));
                    $this->addTag($value['sales_email'], $this->getTagValue('bone_marrow_shared_date'), $value['pheramor_id']);
                }

                $product['created_at'] = \Carbon\Carbon::now();
                $product['updated_at'] = \Carbon\Carbon::now();

                array_push($create_data, $product);
            }
        }

        Product::insert($create_data);

        return response()->json(['status' => true], 200);
    }

    // Add a tag to AgileCRM contact
    protected function addTag($email, $tag, $pheramor_id) {

        $fields = array(
            'email' => urlencode($email),
            'tags' => urlencode('[' . $tag . ']')
        );

        $fields_string = '';

        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        $status = $this->curl_wrap("contacts/email/tags/add", rtrim($fields_string, '&'), "POST", "application/x-www-form-urlencoded");

        // Register logout activity
        $auth_name = Auth::guard('web')->user()->name;
        $auth_id = Auth::guard('web')->user()->id;
        $log_text = '';
        if($status == true) {
            $log_text = $auth_name . ' added new tag (' . $tag . ') to ' . $pheramor_id . ' on AgileCRM at ' . date('Y-m-d h:m:s') . '.';
        } else {
            $log_text = $auth_name . ' failed to add new tag (' . $tag . ') to ' . $pheramor_id . ' on AgileCRM at ' . date('Y-m-d h:m:s') . '.';
        }
        
        activity('agile')
            ->causedBy($auth_id)
            ->log($log_text);
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
