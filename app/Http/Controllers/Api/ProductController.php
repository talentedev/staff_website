<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Product;
use App\Tag;
use App\Config;
use Spatie\Activitylog\Models\Activity;
use Log;

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

            $tags = array();

            if($request->get('pheramor_id') != null) {
                $this->product->pheramor_id = $request->get('pheramor_id');
            }

            if($request->get('sales_email') != null) {
                $this->product->sales_email = $request->get('sales_email');
            }

            if($request->get('first_name') != null) {
                $this->product->first_name = $request->get('first_name');
                $tags = array(Auth::user()->source, Auth::user()->tag, $this->getTagValue('sales_date'));
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

            // Create or update the contact on AgileCRM
            $agileContact = json_decode($this->getContact($request->get('sales_email')));

            if (!empty((array)$agileContact)) {
                $agileID = substr($this->getContact($request->get('sales_email')), 6, 16);
                
                $this->updateContact(
                    $agileID,
                    $request->get('sales_email'),
                    $request->get('pheramor_id'),
                    $request->get('first_name'),
                    $request->get('last_name'),
                    $request->get('phone'),
                    $tags
                );

                return $this->respond([
                    'status' => true,
                    'data'   => $this->product->orderBy('updated_at', 'desc')->first()
                ]);
            } else {
                $this->addContact(
                    $request->get('sales_email'),
                    $request->get('pheramor_id'),
                    $request->get('first_name'),
                    $request->get('last_name'),
                    $request->get('phone'),
                    $tags
                );

                return $this->respond([
                    'status' => true,
                    'data'   => $this->product->orderBy('updated_at', 'desc')->first()
                ]);
            }
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

            $tags = array();

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
                array_push($tags, $this->getTagValue('sales_date'));
            }

            if($request->get('ship_date') != null) {
                $product->ship_date = $request->get('ship_date');
                array_push($tags, $this->getTagValue('ship_date'));
            }
            
            if($request->get('account_connected_date') != null) {
                $product->account_connected_date = $request->get('account_connected_date');
                array_push($tags, $this->getTagValue('account_connected_date'));
            }

            if($request->get('swab_returned_date') != null) {
                $product->swab_returned_date = $request->get('swab_returned_date');
                array_push($tags, $this->getTagValue('swab_returned_date'));
            }

            if($request->get('ship_to_lab_date') != null) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                array_push($tags, $this->getTagValue('ship_to_lab_date'));
            }

            if($request->get('lab_received_date') != null) {
                $product->lab_received_date = $request->get('lab_received_date');
                array_push($tags, $this->getTagValue('lab_received_date'));
            }

            if($request->get('sequenced_date') != null) {
                $product->sequenced_date = $request->get('sequenced_date');
                array_push($tags, $this->getTagValue('sequenced_date'));
            }

            if($request->get('uploaded_to_server_date') != null) {
                $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
                array_push($tags, $this->getTagValue('uploaded_to_server_date'));
            }

            if($request->get('bone_marrow_consent_date') != null) {
                $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
                array_push($tags, $this->getTagValue('bone_marrow_consent_date'));
            }

            if($request->get('bone_marrow_shared_date') != null) {
                $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
                array_push($tags, $this->getTagValue('bone_marrow_shared_date'));
            }

            if($product->sales_email != null) {
                $this->addTag($product->sales_email, $tags, $id);
            }

            $product->save();

            return $this->respond([
                'status' => true,
                'data'   => $this->product->orderBy('updated_at', 'desc')->first()
            ]);
        }
        catch(\Exception $e){
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
            $auth_name = Auth::user()->name;
            $auth_id = Auth::user()->id;
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
    protected function addContact($email, $pheramor_id, $first_name, $last_name, $phone, $tags) {
        $contact_json = array(
          "tags"=>$tags,
          "properties"=>array(
            array(
              "name"=>"email",
              "value"=>$email,
              "type"=>"SYSTEM"
            ),
            array(
              "name"=>"first_name",
              "value"=>$first_name,
              "type"=>"SYSTEM"
            ),
            array(
              "name"=>"last_name",
              "value"=>$last_name,
              "type"=>"SYSTEM"
            ),
            array(
              "name"=>"Phone Number",
              "value"=>$phone,
              "type"=>"CUSTOM"
            ),
            array(
                "name"=>"Pheramor ID",
                "value"=>$pheramor_id,
                "type"=>"CUSTOM"
            )
          )
        );

        $contact_json = json_encode($contact_json);
        $status = $this->curl_wrap("contacts", $contact_json, "POST", "application/json");

        // Register log activity
        $auth_name = Auth::user()->name;
        $auth_id = Auth::user()->id;
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

    // Update contact to AgileCRM
    protected function updateContact($id, $email, $pheramor_id, $first_name, $last_name, $phone, $tags) {
        $contact_json = array(
          "id"=>$id,
          "properties"=>array(
            array(
              "name"=>"first_name",
              "value"=>$first_name,
              "type"=>"SYSTEM"
            ),
            array(
              "name"=>"last_name",
              "value"=>$last_name,
              "type"=>"SYSTEM"
            ),
            array(
              "name"=>"Phone Number",
              "value"=>$phone,
              "type"=>"CUSTOM"
            ),
            array(
                "name"=>"Pheramor ID",
                "value"=>$pheramor_id,
                "type"=>"CUSTOM"
            )
          )
        );

        $contact_json = json_encode($contact_json);
        $status = $this->curl_wrap("contacts/edit-properties", $contact_json, "PUT", "application/json");

        $contact_json1 = array(
          "id"=>$id,
          "tags" => $tags
        );

        $contact_json1 = json_encode($contact_json1);
        $status1 = $this->curl_wrap("contacts/edit/tags", $contact_json1, "PUT", "application/json");

        // Register log activity
        $auth_name = Auth::user()->name;
        $auth_id = Auth::user()->id;
        $log_text = '';
        if($status == true) {
            $log_text = $auth_name . ' updated the contact (' . $pheramor_id . ', ' . $email . ') to AgileCRM at ' . date('Y-m-d h:m:s') . '.';
        } else {
            $log_text = $auth_name . ' failed to update the contact (' . $pheramor_id . ', ' . $email . ') to AgileCRM at ' . date('Y-m-d h:m:s') . '.';
        }
        
        activity('agile')
            ->causedBy($auth_id)
            ->log($log_text);
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
            return $this->respond([
                'status' => false,
                'message' => 'We can not update phone number.'
            ]);
        }
    }
}
