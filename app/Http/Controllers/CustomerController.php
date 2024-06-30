<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
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
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
            'name' => 'required|string'
        ]);

        $customer = new Customer();
        $customer->name=$request->name;
        $customer->email=$request->email;
        $customer->readable_password = $request->password;
        $customer->password = Hash::make($request->password);
        $customer->save();

       $token = $customer->createToken('Customer Token')->accessToken;

        return response()->json(['token' => $token,'message' => 'Customer Added Successfully'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function customerLogin(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            // Authentication succeeded
            $token = $customer->createToken('Customer Token')->accessToken;
            return response()->json(['token' => $token,'message' => 'Login Successfully']);

        }

        // Authentication failed
        return response()->json(['success' => false, 'error' => 'Email or Password is wrong']);
    }
}
