<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Product;

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
            $this->product->pheramor_id = $request->get('pheramor_id');
            $this->product->sales_email = $request->get('sales_email');
            $this->product->source = Auth::user()->source;

            $this->product->save();
            return $this->respond([
                'status' => true,
                'data'   => $this->product->orderBy('created_at', 'desc')->first()
            ]);
        }
        catch(\Exception $e){
            return $this->respond([
                'status' => false,
                'message' => 'Pheramor ID or Email alrady exist'
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
        try{
            $product = $this->product::where('pheramor_id', $id)->get()->first();

            if($request->get('sales_date') != null) {
                $product->sales_date = $request->get('sales_date');
            }

            if($request->get('ship_date') != null) {
                $product->ship_date = $request->get('ship_date');
            }
            
            if($request->get('account_connected_date') != null) {
                $product->account_connected_date = $request->get('account_connected_date');
            }

            if($request->get('swab_returned_date') != null) {
                $product->swab_returned_date = $request->get('swab_returned_date');
            }

            if($request->get('ship_to_lab_date') != null) {
                $product->ship_to_lab_date = $request->get('ship_to_lab_date');
            }

            if($request->get('lab_received_date') != null) {
                $product->lab_received_date = $request->get('lab_received_date');
            }

            if($request->get('sequenced_date') != null) {
                $product->sequenced_date = $request->get('sequenced_date');
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
            return $this->respond([
                'status' => false,
                'message' => 'Pheramor ID dont exist'
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
}
