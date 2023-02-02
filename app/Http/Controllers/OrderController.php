<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Orders_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePemesananRequest;
use App\Http\Requests\UpdatePemesananRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request,[
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'check_in' => 'required',
            'check_out' => 'required',
            'guest_name' => 'required',
            'rooms_amount' => 'required',
            'type_id' => 'required',
        ]);
        $customer_name = $request->customer_name;
        $type_id = $request->type_id;
        $hash = strlen($customer_name);
        $order_number = $type_id * $hash;

        $rooms_amount = DB::table('type')->count();

        $fdate = '2023-01-01';
        $tdate = '2023-01-04';
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');//now do whatever you like with $days

       

        Order::create([
            'order_number' => $order_number,
            'customer_name' =>$request->customer_name ,
            'customer_email'=>$request->customer_email,
            'check_in' =>$request->check_in,
            'check_out' =>$request->check_out,
            'guest_name' =>$request->guest_name,
            'rooms_amount' =>$request->rooms_amount,
            'type_id' =>$request->type_id,
        ]);

        
        // Orders_Detail::create([
        // ]);

        return response()->json([
            'message' => 'Success!!',
            'data' => Order::all(),
            $rooms_amount,
            $days   
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePemesananRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'order_number' => 'required',
            'customer' => 'required',
            'customer_email' => 'required',
            'order_date' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'guest_name' => 'required',
            'rooms_amount' => 'required',
            'type_id' => 'required',
            'user_id' => 'required',
        ]);
        Order::create([
            'order_number' => $request->order_number,
            'customer' => $request->customer,
            'customer_email' => $request->customer_email,
            'order_date' => $request->order_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guest_name' => $request->guest_name,
            'rooms_amount' => $request->rooms_amount,
            'type_id' => $request->type_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json([
            'message' => 'Success!!',
            'data' => Order::all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function show(Request $pemesanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $pemesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePemesananRequest  $request
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $pemesanan)
    {
        //
    }
}
