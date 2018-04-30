<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Customer;

class CustomerController extends Controller
{
    /**
     * @var Customer
     */
    private $customer;

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
    public function __construct(Customer $customer)
    {
        $this->middleware('auth:web');
        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->customer->get();
        return view('admin.customers', [
            'customers' => $customers
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
        $this->customer->name = $request->get('name');
        $this->customer->sales_email = $request->get('email');
        $this->customer->note = $request->get('note');
        $this->customer->source = Auth::user()->roles[0]->name;

        $this->customer->save();
        $message = 'Pheramor account successfully added';

        return response()->json(['message' => $message], 200);
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
        $customer = $this->customer::find($id);

        $customer->name = $request->get('name');
        $customer->sales_email = $request->get('email');
        $customer->note = $request->get('note');

        $customer->save();
        $message = 'Customer successfully updated';

        return response()->json(['message' => $message], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->customer->destroy($id);
        $message = 'Customer is deleted successfully';
        return response()->json(['message' => $message], 200);
    }
}
