<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Product;

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
        $product->account_connected_date = $request->get('account_connected_date');
        $product->swab_returned_date = $request->get('swab_returned_date');
        $product->ship_to_lab_date = $request->get('ship_to_lab_date');
        $product->lab_received_date = $request->get('lab_received_date');
        $product->sequenced_date = $request->get('sequenced_date');
        $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
        $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
        $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');
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
                }
                
                if ($request->get('account_connected_date') != '') {
                   $product->account_connected_date = $request->get('account_connected_date');
                }

                if ($request->get('swab_returned_date') != '') {
                   $product->swab_returned_date = $request->get('swab_returned_date');
                }

                if ($request->get('ship_to_lab_date') != '') {
                   $product->ship_to_lab_date = $request->get('ship_to_lab_date');
                }

                if ($request->get('lab_received_date') != '') {
                   $product->lab_received_date = $request->get('lab_received_date');
                }

                if ($request->get('sequenced_date') != '') {
                   $product->sequenced_date = $request->get('sequenced_date');
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

            $product->sales_date = $request->get('sales_date');
            $product->account_connected_date = $request->get('account_connected_date');
            $product->swab_returned_date = $request->get('swab_returned_date');
            $product->ship_to_lab_date = $request->get('ship_to_lab_date');
            $product->lab_received_date = $request->get('lab_received_date');
            $product->sequenced_date = $request->get('sequenced_date');
            $product->uploaded_to_server_date = $request->get('uploaded_to_server_date');
            $product->bone_marrow_consent_date = $request->get('bone_marrow_consent_date');
            $product->bone_marrow_shared_date = $request->get('bone_marrow_shared_date');

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
        $create_data = array();

        foreach ($data as $key => $value) {

            if ($value['id'] != '') { // Update customer

                $product = array();

                $product['sales_date'] = date('Y-m-d', strtotime($value['sales_date']));
                $product['account_connected_date'] = date('Y-m-d', strtotime($value['account_connected_date']));
                $product['swab_returned_date'] = date('Y-m-d', strtotime($value['swab_returned_date']));
                $product['ship_to_lab_date'] = date('Y-m-d', strtotime($value['ship_to_lab_date']));
                $product['lab_received_date'] = date('Y-m-d', strtotime($value['lab_received_date']));
                $product['sequenced_date'] = date('Y-m-d', strtotime($value['sequenced_date']));
                $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($value['uploaded_to_server_date']));
                $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($value['bone_marrow_consent_date']));
                $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($value['bone_marrow_shared_date']));
                $product['note'] = $value['note'];

                Product::where('id', $value['id'])->update($product);
            } else { // Create customer

                $product = array();

                $product['pheramor_id'] = $value['pheramor_id'];
                $product['sales_email'] = $value['sales_email'];
                $product['account_email'] = $value['account_email'];
                $product['source'] = Auth::user()->roles[0]->name;
                $product['sales_date'] = date('Y-m-d', strtotime($value['sales_date']));
                $product['account_connected_date'] = date('Y-m-d', strtotime($value['account_connected_date']));
                $product['swab_returned_date'] = date('Y-m-d', strtotime($value['swab_returned_date']));
                $product['ship_to_lab_date'] = date('Y-m-d', strtotime($value['ship_to_lab_date']));
                $product['lab_received_date'] = date('Y-m-d', strtotime($value['lab_received_date']));
                $product['sequenced_date'] = date('Y-m-d', strtotime($value['sequenced_date']));
                $product['uploaded_to_server_date'] = date('Y-m-d', strtotime($value['uploaded_to_server_date']));
                $product['bone_marrow_consent_date'] = date('Y-m-d', strtotime($value['bone_marrow_consent_date']));
                $product['bone_marrow_shared_date'] = date('Y-m-d', strtotime($value['bone_marrow_shared_date']));
                $product['note'] = $value['note'];

                array_push($create_data, $product);
            }
        }

        Product::insert($create_data);

        return response()->json(['status' => true], 200);
    }
}
